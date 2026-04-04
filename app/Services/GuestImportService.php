<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\MessageTemplate;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class GuestImportService
{
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
     * Generate WhatsApp URL for a single guest.
     */
    public function generateWhatsAppUrl(Invitation $invitation, string $invitationUrl, string $guestName, ?string $phone = null): string
    {
        $personalUrl = $invitationUrl.'?kpd='.rawurlencode($guestName);

        $template = MessageTemplate::where('slug', 'universal')->first();
        $templateContent = $template ? $template->content : "Kepada Yth. {nama},\n\nKami mengundang Bapak/Ibu/Saudara/i untuk hadir pada acara pernikahan kami:\n\n{judul}\n\n{detail_acara}\n\nSilakan buka klik link berikut untuk info selengkapnya:\n{link}\n\nKehadiran dan doa restu Anda adalah kebahagiaan bagi kami.\nTerima kasih.";

        // Generate Title
        $inv = $invitation;
        $invitationTitle = $inv->title;
        if ($inv->groom_name && $inv->bride_name) {
            $styles = $inv->custom_styles ?? [];
            $order = $styles['name_order'] ?? 'groom_first';
            $first = $order === 'bride_first' ? $inv->bride_name : $inv->groom_name;
            $second = $order === 'bride_first' ? $inv->groom_name : $inv->bride_name;
            $invitationTitle = "The Wedding of {$first} dan {$second}";
        }

        // Generate Event Details
        $details = [];
        if ($inv->akad_date) {
            $akadDate = Carbon::parse($inv->akad_date);
            $akadTime = $inv->akad_time ? Carbon::parse($inv->akad_time)->format('H:i') : '';

            $details[] = 'Pada: Akad Pernikahan';
            $details[] = '🗓️ Tanggal: '.$akadDate->translatedFormat('d-m-Y');
            if ($akadTime) {
                $details[] = "🕛 Pukul: {$akadTime} - Selesai";
            }
            if ($inv->akad_address) {
                $details[] = "📍 Lokasi: {$inv->akad_address}";
            }
            $details[] = '';
        }
        if ($inv->resepsi_date) {
            $receptionDate = Carbon::parse($inv->resepsi_date);
            $receptionTime = $inv->resepsi_time ? Carbon::parse($inv->resepsi_time)->format('H:i') : '';

            $details[] = 'Pada: Resepsi Pernikahan';
            $details[] = '🗓️ Tanggal: '.$receptionDate->translatedFormat('d-m-Y');
            if ($receptionTime) {
                $details[] = "🕛 Pukul: {$receptionTime} - Selesai";
            }
            if ($inv->resepsi_address) {
                $details[] = "📍 Lokasi: {$inv->resepsi_address}";
            }
        }
        $eventDetails = implode("\n", $details);

        $message = str_replace(
            ['{nama}', '{judul}', '{detail_acara}', '{link}'],
            [$guestName, $invitationTitle, $eventDetails, $personalUrl],
            $templateContent
        );

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

        if ($invitation->akad_date) {
            $akadDate = Carbon::parse($invitation->akad_date);
            $akadTime = $invitation->akad_time ? Carbon::parse($invitation->akad_time)->format('H:i') : '';

            $details[] = 'Pada: Akad Pernikahan';
            $details[] = "\u{1F5D3}\u{FE0F} Tanggal: ".$akadDate->translatedFormat('d-m-Y');
            if ($akadTime) {
                $details[] = "\u{1F55B} Pukul: {$akadTime} - Selesai";
            }
            if ($invitation->akad_address) {
                $details[] = "\u{1F4CD} Lokasi: {$invitation->akad_address}";
            }
            $details[] = '';
        }

        if ($invitation->resepsi_date) {
            $receptionDate = Carbon::parse($invitation->resepsi_date);
            $receptionTime = $invitation->resepsi_time ? Carbon::parse($invitation->resepsi_time)->format('H:i') : '';

            $details[] = 'Pada: Resepsi Pernikahan';
            $details[] = "\u{1F5D3}\u{FE0F} Tanggal: ".$receptionDate->translatedFormat('d-m-Y');
            if ($receptionTime) {
                $details[] = "\u{1F55B} Pukul: {$receptionTime} - Selesai";
            }
            if ($invitation->resepsi_address) {
                $details[] = "\u{1F4CD} Lokasi: {$invitation->resepsi_address}";
            }
        }

        return implode("\n", $details);
    }
}
