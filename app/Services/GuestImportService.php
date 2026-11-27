<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\MessageTemplate;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class GuestImportService
{
    public const INSTAGRAM_DM_MAX_UTF16_LENGTH = 950;
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
     * Generate formatted invitation share message for a single guest.
     */
    public function generateShareMessage(Invitation $invitation, string $invitationUrl, string $guestName): string
    {
        $personalUrl = $invitationUrl.'?kpd='.rawurlencode($guestName);

        $template = MessageTemplate::where('slug', 'universal')->first();
        $templateContent = $template ? $template->content : "Kepada Yth. {nama},\n\nKami mengundang Bapak/Ibu/Saudara/i untuk hadir pada acara pernikahan kami:\n\n{judul}\n\n{detail_acara}\n\nSilakan buka klik link berikut untuk info selengkapnya:\n{link}\n\nKehadiran dan doa restu Anda adalah kebahagiaan bagi kami.\nTerima kasih.";

        $invitationTitle = $this->getInvitationTitle($invitation);
        $eventDetails = $this->formatEventDetails($invitation);

        return str_replace(
            ['{nama}', '{judul}', '{detail_acara}', '{link}'],
            [$guestName, $invitationTitle, $eventDetails, $personalUrl],
            $templateContent
        );
    }

    /**
     * Generate a compact invitation share message tailored for Instagram DM on iOS (<= 1,000 UTF-16 code units).
     */
    public function generateInstagramShareMessage(
        Invitation $invitation,
        string $invitationUrl,
        string $guestName,
        int $maxUtf16Length = self::INSTAGRAM_DM_MAX_UTF16_LENGTH
    ): string {
        $personalUrl = $invitationUrl.'?kpd='.rawurlencode($guestName);
        $invitationTitle = $this->getInvitationTitle($invitation);

        $buildMessage = function (?string $eventDetails, bool $compactIntro = false) use ($guestName, $invitationTitle, $personalUrl): string {
            $lines = [
                "Kepada Yth. {$guestName}",
                '',
                $compactIntro
                    ? "Kami mengundang Bapak/Ibu/Saudara/i hadir di acara {$invitationTitle}."
                    : "Dengan hormat, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara:\n{$invitationTitle}",
            ];

            if ($eventDetails !== null && $eventDetails !== '') {
                $lines[] = '';
                $lines[] = $eventDetails;
            }

            $lines[] = '';
            $lines[] = "Info lengkap & konfirmasi kehadiran:\n{$personalUrl}";

            if (! $compactIntro) {
                $lines[] = '';
                $lines[] = 'Merupakan kehormatan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir. Terima kasih.';
            }

            return $this->normalizeLineEndings(implode("\n", $lines));
        };

        $candidates = [
            $buildMessage($this->formatCompactEventDetails($invitation, true, null), false),
            $buildMessage($this->formatCompactEventDetails($invitation, true, 70), false),
            $buildMessage($this->formatCompactEventDetails($invitation, false, null), false),
            $buildMessage(null, true),
        ];

        foreach ($candidates as $candidate) {
            if ($this->utf16Length($candidate) <= $maxUtf16Length) {
                return $candidate;
            }
        }

        // Final safety net: preserve the full personal link and trim prefix text cleanly by codepoint.
        $suffix = "\n\n{$personalUrl}";
        $prefix = "Kepada Yth. {$guestName}\n\nUndangan {$invitationTitle}";

        while ($prefix !== '' && $this->utf16Length($prefix.$suffix) > $maxUtf16Length) {
            $prefix = mb_substr($prefix, 0, max(0, mb_strlen($prefix, 'UTF-8') - 1), 'UTF-8');
            $prefix = rtrim($prefix);
        }

        return $this->normalizeLineEndings($prefix !== '' ? $prefix.$suffix : $personalUrl);
    }

    /**
     * Format compact event details without verbose dividers, optionally capping venue/address length.
     */
    private function formatCompactEventDetails(Invitation $invitation, bool $includeAddress = true, ?int $maxAddressLength = null): string
    {
        $details = [];
        $eventType = $invitation->event_type;

        $events = [
            [
                'enabled' => in_array($eventType, ['akad_only', 'both'], true) && $invitation->akad_date,
                'label' => 'Akad Nikah',
                'date' => $invitation->akad_date,
                'key' => 'akad',
                'address' => $invitation->akad_address ?: $invitation->akad_venue,
            ],
            [
                'enabled' => in_array($eventType, ['resepsi_only', 'both'], true) && $invitation->resepsi_date,
                'label' => 'Resepsi',
                'date' => $invitation->resepsi_date,
                'key' => 'resepsi',
                'address' => $invitation->resepsi_address ?: $invitation->resepsi_venue,
            ],
        ];

        foreach ($events as $event) {
            if (! $event['enabled']) {
                continue;
            }

            $dateStr = Carbon::parse($event['date'])->translatedFormat('d-m-Y');
            $timeStr = $invitation->formatEventTime($event['key'], true);
            $details[] = "{$event['label']}: {$dateStr}, {$timeStr}";

            if ($includeAddress && ! empty($event['address'])) {
                $address = trim(preg_replace('/\s+/', ' ', (string) $event['address']));
                if ($maxAddressLength !== null && mb_strlen($address, 'UTF-8') > $maxAddressLength) {
                    $address = rtrim(mb_substr($address, 0, max(1, $maxAddressLength - 3), 'UTF-8')).'...';
                }
                $details[] = "Lokasi: {$address}";
            }
        }

        return implode("\n", $details);
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
     * Format event details for WhatsApp message.
     */
    public function formatEventDetails(Invitation $invitation): string
    {
        $details = [];
        $eventType = $invitation->event_type;

        if (in_array($eventType, ['akad_only', 'both'], true) && $invitation->akad_date) {
            $akadDate = Carbon::parse($invitation->akad_date);
            $akadTime = $invitation->formatEventTime('akad', true);

            $details[] = 'Pada: Akad Pernikahan';
            $details[] = "\u{1F5D3}\u{FE0F} Tanggal: ".$akadDate->translatedFormat('d-m-Y');
            $details[] = "\u{1F55B} Pukul: {$akadTime}";
            if ($invitation->akad_address) {
                $details[] = "\u{1F4CD} Lokasi: {$invitation->akad_address}";
            }
            $details[] = '';
        }

        if (in_array($eventType, ['resepsi_only', 'both'], true) && $invitation->resepsi_date) {
            $receptionDate = Carbon::parse($invitation->resepsi_date);
            $receptionTime = $invitation->formatEventTime('resepsi', true);

            $details[] = 'Pada: Resepsi Pernikahan';
            $details[] = "\u{1F5D3}\u{FE0F} Tanggal: ".$receptionDate->translatedFormat('d-m-Y');
            $details[] = "\u{1F55B} Pukul: {$receptionTime}";
            if ($invitation->resepsi_address) {
                $details[] = "\u{1F4CD} Lokasi: {$invitation->resepsi_address}";
            }
        }

        return implode("\n", $details);
    }
}
