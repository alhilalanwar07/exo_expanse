<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Seeder;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Universal',
                'slug' => 'universal',
                'icon' => '🌐',
                'order' => 1,
                'content' => <<<'EOT'
Yth. Bapak/Ibu/Saudara/i
{nama}
Di Tempat
----------

Dengan segala kerendahan hati, kami mengundang Bapak/Ibu/Saudara/i dan teman-teman untuk menghadiri acara,
==========
{judul}
==========

{detail_acara}

Link undangan bisa diakses lengkap di:
{link}

Merupakan suatu kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir di acara kami
Mohon maaf perihal undangan hanya di bagikan melalui pesan ini
Terima kasih banyak atas perhatiannya 🙏
EOT,
            ],
            [
                'name' => 'Universal 2',
                'slug' => 'universal-2',
                'icon' => '💌',
                'order' => 2,
                'content' => <<<'EOT'
Assalamualaikum Wr. Wb.

Kepada Yth.
*{nama}*

Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk hadir di acara pernikahan kami:

{judul}

{detail_acara}

Silakan buka undangan digital kami:
{link}

Merupakan suatu kehormatan bagi kami jika Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.

Wassalamualaikum Wr. Wb. 🤲
EOT,
            ],
            [
                'name' => 'Muslim',
                'slug' => 'muslim',
                'icon' => '🕌',
                'order' => 3,
                'content' => <<<'EOT'
بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ

Assalamualaikum Wr. Wb.

Kepada Yth.
*{nama}*
Di Tempat

Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami:

{judul}

{detail_acara}

Link Undangan:
{link}

Merupakan suatu kehormatan dan kebahagiaan bagi kami, apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.

Atas kehadiran dan doa restunya, kami ucapkan terima kasih.

Wassalamualaikum Wr. Wb. 🤲
EOT,
            ],
            [
                'name' => 'Formal',
                'slug' => 'formal',
                'icon' => '👔',
                'order' => 4,
                'content' => <<<'EOT'
Yth.
{nama}
Di Tempat

Dengan hormat,

Bersama ini kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri resepsi pernikahan kami:

{judul}

{detail_acara}

Undangan lengkap dapat diakses melalui:
{link}

Atas kehadiran dan doa restu Bapak/Ibu/Saudara/i, kami mengucapkan terima kasih.

Hormat kami.
EOT,
            ],
            [
                'name' => 'Santai',
                'slug' => 'casual',
                'icon' => '🎉',
                'order' => 5,
                'content' => <<<'EOT'
Hai {nama}! 👋

Akhirnya kami mau nikahan! 💍✨

{judul}

{detail_acara}

Yuk lihat undangan kami:
{link}

Ditunggu kehadirannya ya! Semoga bisa ketemu dan rayakan bareng 🥳

Salam hangat 💕
EOT,
            ],
            [
                'name' => 'English',
                'slug' => 'english',
                'icon' => '🌍',
                'order' => 6,
                'content' => <<<'EOT'
Dear {nama},

You are cordially invited to celebrate our wedding:

{judul}

{detail_acara}

Please view our invitation:
{link}

We would be honored by your presence.
Hope to see you there! 💕

With love.
EOT,
            ],
        ];

        foreach ($templates as $template) {
            MessageTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
