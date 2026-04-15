<?php

namespace App\Http\Controllers;

use App\Models\Siswakkri;
use App\Models\SiswakkriHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SiswakkriController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'social_platform' => ['required', 'string', 'max:40'],
            'social_account' => ['required', 'string', 'max:150'],
            'age' => ['required', 'integer', 'between:1,120'],
        ]);

        $payload = [
            'name' => Str::upper($this->normalizeText($validated['name'])),
            'social_platform' => Str::lower($this->normalizeText($validated['social_platform'])),
            'social_account' => $this->normalizeText($validated['social_account']),
            'age' => (int) $validated['age'],
        ];

        $replacedPrevious = false;

        $siswakkri = DB::transaction(function () use (&$replacedPrevious, $payload, $request): Siswakkri {
            $existingQuery = Siswakkri::query()->where('name', $payload['name']);
            $replacedPrevious = $existingQuery->exists();

            if ($replacedPrevious) {
                $existingQuery->delete();
            }

            $record = Siswakkri::query()->create([
                ...$payload,
                'last_submitted_at' => now(),
            ]);

            SiswakkriHistory::query()->create([
                'siswakkri_id' => $record->id,
                ...$payload,
                'replaced_previous' => $replacedPrevious,
                'submitted_from_ip' => $request->ip(),
                'submitted_at' => now(),
            ]);

            return $record;
        });

        return response()->json([
            'message' => $replacedPrevious
                ? 'Data lama untuk nama ini dihapus dan diganti dengan data baru.'
                : 'Data berhasil disimpan.',
            'replaced_previous' => $replacedPrevious,
            'data' => [
                'name' => $siswakkri->name,
                'social_platform' => $siswakkri->social_platform,
                'social_account' => $siswakkri->social_account,
                'age' => $siswakkri->age,
            ],
        ]);
    }

    public function migrateSpecificTables(): JsonResponse
    {
        $migrationPaths = [
            'database/migrations/2026_04_15_100000_create_siswakkris_table.php',
            'database/migrations/2026_04_15_100100_create_siswakkri_histories_table.php',
        ];

        $outputs = [];

        foreach ($migrationPaths as $path) {
            Artisan::call('migrate', [
                '--path' => $path,
                '--force' => true,
            ]);

            $outputs[$path] = trim(Artisan::output()) ?: 'OK';
        }

        return response()->json([
            'message' => 'Migrasi spesifik Siswakkri selesai.',
            'migrations' => $outputs,
        ]);
    }

    private function normalizeText(string $value): string
    {
        return preg_replace('/\s+/', ' ', trim($value)) ?: '';
    }
}
