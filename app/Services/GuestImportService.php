<?php

namespace App\Services;

use App\Models\Invitation;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class GuestImportService
{
    public const INSTAGRAM_DM_MAX_UTF16_LENGTH = 850;
    /**
     * Parse comma-separated text into guest array.
     * Input: "Bapak Budi, Ibu Siti, Pak Ahmad"
     * Output: [['name' => 'Bapak Budi'], ['name' => 'Ibu Siti'], ...]
     */
    public function parseCommaSeparated(string $text): array
    {
        if (empty(trim($text))) {
            return [];
        }

        $names = preg_split('/[,\n]+/', $text);
        $guests = [];

        foreach ($names as $name) {
            $name = trim($name);
            if (! empty($name)) {
                $guests[] = ['name' => $name];
            }
        }

        return $guests;
    }

    /**
     * Parse CSV/Excel file into guest array.
     * Expects columns: name, phone (optional)
     */
    public function parseCsvFile(UploadedFile $file): array
    {
        $guests = [];
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return [];
        }

        // Skip header row
        $header = fgetcsv($handle);
        $nameIndex = $this->findColumnIndex($header, ['name', 'nama', 'tamu', 'guest']);
        $phoneIndex = $this->findColumnIndex($header, ['phone', 'telepon', 'hp', 'no_hp', 'nomor']);

        if ($nameIndex === null) {
            // No header found, treat first column as name
            rewind($handle);
            $nameIndex = 0;
        }

        while (($row = fgetcsv($handle)) !== false) {
            $name = trim($row[$nameIndex] ?? '');

            if (! empty($name)) {
                $guest = ['name' => $name];

                if ($phoneIndex !== null && isset($row[$phoneIndex])) {
                    $phone = trim($row[$phoneIndex]);
                    if (! empty($phone)) {
                        $guest['phone_number'] = $this->normalizePhone($phone);
                    }
                }

                $guests[] = $guest;
            }
        }

        fclose($handle);

        return $guests;
    }

    /**
     * Find column index by possible header names.
     */
    private function findColumnIndex(?array $header, array $possibleNames): ?int
    {
        if ($header === null) {
            return null;
        }

        foreach ($header as $index => $column) {
            $column = Str::lower(trim($column));
            if (in_array($column, $possibleNames)) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Normalize phone number to Indonesian format.
     */
    private function normalizePhone(string $phone): string
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert 08xx to 628xx
        if (Str::startsWith($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        // Add 62 if not present
        if (! Str::startsWith($phone, '62')) {
            $phone = '62'.$phone;
        }

        return $phone;
    }

    /**
     * Validate and clean guest data.
     */
    public function validateGuests(array $guests): array
    {
        return array_filter($guests, function ($guest) {
            return ! empty($guest['name']) && strlen($guest['name']) <= 255;
        });
    }

    /**
     * Generate formatted invitation share message for a single guest (aligned across WhatsApp and Instagram DM).
     */
    public function generateShareMessage(Invitation $invitation, string $invitationUrl, string $guestName): string
    {
        return $this->generateInstagramShareMessage($invitation, $invitationUrl, $guestName);
    }

    /**
     * Generate an aligned invitation share message matching the WhatsApp universal format while staying within Instagram iOS limits.
     */
    public function generateInstagramShareMessage(
        Invitation $invitation,
        string $invitationUrl,
        string $guestName,
        int $maxUtf16Length = self::INSTAGRAM_DM_MAX_UTF16_LENGTH
    ): string {
        $personalUrl = $invitationUrl.'?kpd='.rawurlencode($guestName);
        $invitationTitle = $this->getInvitationTitle($invitation);

        $buildMessage = function (?string $eventDetails, string $closing = '') use ($guestName, $invitationTitle, $personalUrl): string {
            $lines = [
                'Yth. Bapak/Ibu/Saudara/i',
                $guestName,
                'Di Tempat',
                '-----------',
                'Dengan segala kerendahan hati, kami mengundang Bapak/Ibu/Saudara/i dan teman-teman untuk menghadiri acara,',
                '===========',
                $invitationTitle,
                '===========',
            ];

            if ($eventDetails !== null && $eventDetails !== '') {
                $lines[] = $eventDetails;
            }

            $lines[] = '';
            $lines[] = 'Link undangan bisa diakses lengkap di:';
            $lines[] = $personalUrl;

            if ($closing !== '') {
                $lines[] = '';
                $lines[] = $closing;
            }

            return $this->normalizeLineEndings(implode("\n", $lines));
        };

        $fullClosing = "Merupakan suatu kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir di acara kami\nTerima kasih banyak atas perhatiannya";
        $shortClosing = 'Terima kasih banyak atas perhatiannya';

        $fullDetails = $this->formatEventDetails($invitation, true, null);
        $trimmedAddressDetails = $this->formatEventDetails($invitation, true, 65);
        $noAddressDetails = $this->formatEventDetails($invitation, false, null);

        $candidates = [
            $buildMessage($fullDetails, $fullClosing),
            $buildMessage($fullDetails, $shortClosing),
            $buildMessage($fullDetails, ''),
            $buildMessage($trimmedAddressDetails, ''),
            $buildMessage($noAddressDetails, ''),
            $buildMessage(null, ''),
        ];

        foreach ($candidates as $candidate) {
            if ($this->utf16Length($candidate) <= $maxUtf16Length) {
                return $candidate;
            }
        }

        // Final safety net: preserve the full personal link and trim prefix text cleanly by codepoint.
        $suffix = "\n\nLink undangan bisa diakses lengkap di:\n{$personalUrl}";
        $prefix = "Yth. Bapak/Ibu/Saudara/i\n{$guestName}\n\n{$invitationTitle}";

        while ($prefix !== '' && $this->utf16Length($prefix.$suffix) > $maxUtf16Length) {
            $prefix = mb_substr($prefix, 0, max(0, mb_strlen($prefix, 'UTF-8') - 1), 'UTF-8');
            $prefix = rtrim($prefix);
        }

        return $this->normalizeLineEndings($prefix !== '' ? $prefix.$suffix : $personalUrl);
    }

    /**
     * Measure string length in UTF-16 code units (matching iOS NSString.length / JavaScript string.length).
     */
    private function utf16Length(string $text): int
    {
        return intdiv(strlen(mb_convert_encoding($text, 'UTF-16BE', 'UTF-8')), 2);
    }

    private function normalizeLineEndings(string $text): string
    {
        return str_replace(["\r\n", "\r"], "\n", $text);
    }

    /**
     * Generate WhatsApp URL for a single guest.
     */
    public function generateWhatsAppUrl(Invitation $invitation, string $invitationUrl, string $guestName, ?string $phone = null): string
    {
        $message = $this->generateShareMessage($invitation, $invitationUrl, $guestName);
        $encodedMessage = rawurlencode($message);

        if ($phone) {
            return "https://api.whatsapp.com/send?phone={$phone}&text={$encodedMessage}";
        }

        return "https://api.whatsapp.com/send?text={$encodedMessage}";
    }

    /**
     * Get the invitation title.
     */
    public function getInvitationTitle(Invitation $invitation): string
    {
        if ($invitation->groom_name && $invitation->bride_name) {
            $styles = $invitation->custom_styles ?? [];
            $order = $styles['name_order'] ?? 'groom_first';
            $first = $order === 'bride_first' ? $invitation->bride_name : $invitation->groom_name;
            $second = $order === 'bride_first' ? $invitation->groom_name : $invitation->bride_name;

            return "The Wedding of {$first} dan {$second}";
        }

        return $invitation->title;
    }

    /**
     * Format event details for WhatsApp and Instagram share messages.
     */
    public function formatEventDetails(Invitation $invitation, bool $includeAddress = true, ?int $maxAddressLength = null): string
    {
        $blocks = [];
        $eventType = $invitation->event_type;

        $events = [
            [
                'enabled' => in_array($eventType, ['akad_only', 'both'], true) && $invitation->akad_date,
                'title' => 'Pada: Akad Pernikahan',
                'date' => $invitation->akad_date,
                'key' => 'akad',
                'address' => $invitation->akad_address ?: $invitation->akad_venue,
            ],
            [
                'enabled' => in_array($eventType, ['resepsi_only', 'both'], true) && $invitation->resepsi_date,
                'title' => 'Pada: Resepsi Pernikahan',
                'date' => $invitation->resepsi_date,
                'key' => 'resepsi',
                'address' => $invitation->resepsi_address ?: $invitation->resepsi_venue,
            ],
        ];

        foreach ($events as $event) {
            if (! $event['enabled']) {
                continue;
            }

            $lines = [
                $event['title'],
                "\u{1F5D3}\u{FE0F} Tanggal: ".Carbon::parse($event['date'])->translatedFormat('d-m-Y'),
                "\u{1F55B} Pukul: ".$invitation->formatEventTime($event['key'], true),
            ];

            if ($includeAddress && ! empty($event['address'])) {
                $address = trim(preg_replace('/\s+/', ' ', (string) $event['address']));
                if ($maxAddressLength !== null && mb_strlen($address, 'UTF-8') > $maxAddressLength) {
                    $address = rtrim(mb_substr($address, 0, max(1, $maxAddressLength - 3), 'UTF-8')).'...';
                }
                $lines[] = "\u{1F4CD} Lokasi: {$address}";
            }

            $blocks[] = implode("\n", $lines);
        }

        return implode("\n\n", $blocks);
    }
}
