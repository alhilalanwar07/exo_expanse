<?php

namespace Database\Seeders;

use App\Models\Invitation;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Memastikan tidak duplikasi berantai if di-run berkali-kali
        if (User::count() > 50) {
            $this->command->info('Data palsu sudah cukup banyak. Melewati seeding...');

            return;
        }

        // Tembak factory untuk buat 25 pengguna baru
        $users = User::factory(25)->create([
            'role' => 'user',
        ]);

        // Tambahkan 3 admin tambahan supaya seru
        User::factory(3)->create([
            'role' => 'admin',
        ]);

        // Ambil semua tema yang ada di database (hasil ThemeSeeder)
        $themes = Theme::all();

        // Jika karena suatu alasan tidak ada tema, panggil thme seeder terlebih dahulu
        if ($themes->isEmpty()) {
            $this->call(ThemeSeeder::class);
            $themes = Theme::all();
        }

        // Buat undangan secara acak (0 sampai 3 per user)
        $totalUndangan = 0;
        foreach ($users as $user) {
            $jumlahUndangan = rand(0, 3);

            for ($i = 0; $i < $jumlahUndangan; $i++) {
                Invitation::factory()->create([
                    'user_id' => $user->id,
                    'theme_id' => $themes->random()->id,
                ]);
                $totalUndangan++;
            }
        }

        $this->command->info("✅ Berhasil membuat 28 pengguna baru dan {$totalUndangan} undangan palsu untuk dasbor!");
    }
}
