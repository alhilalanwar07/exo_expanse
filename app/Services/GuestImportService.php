<?php

namespace App\Services;

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
            if (!empty($name)) {
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
            
            if (!empty($name)) {
                $guest = ['name' => $name];
                
                if ($phoneIndex !== null && isset($row[$phoneIndex])) {
                    $phone = trim($row[$phoneIndex]);
                    if (!empty($phone)) {
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
            $phone = '62' . substr($phone, 1);
        }
        
        // Add 62 if not present
        if (!Str::startsWith($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Validate and clean guest data.
     */
    public function validateGuests(array $guests): array
    {
        return array_filter($guests, function ($guest) {
            return !empty($guest['name']) && strlen($guest['name']) <= 255;
        });
    }

    /**
     * Generate WhatsApp URL for a single guest.
     */
    public function generateWhatsAppUrl(string $invitationUrl, string $guestName, ?string $phone = null): string
    {
        $personalUrl = $invitationUrl . '?kpd=' . urlencode($guestName);
        
        $message = "Assalamualaikum Wr. Wb.\n\n";
        $message .= "Kepada Yth.\n*{$guestName}*\n\n";
        $message .= "Dengan penuh sukacita, kami mengundang Bapak/Ibu/Saudara/i untuk hadir di acara pernikahan kami.\n\n";
        $message .= "Klik link berikut untuk membuka undangan:\n{$personalUrl}\n\n";
        $message .= "Terima kasih 🙏";

        $encodedMessage = urlencode($message);

        if ($phone) {
            return "https://wa.me/{$phone}?text={$encodedMessage}";
        }

        return "https://wa.me/?text={$encodedMessage}";
    }
}
