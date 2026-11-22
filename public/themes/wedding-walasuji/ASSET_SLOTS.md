# Daftar Slot Aset Tema **Wedding Walasuji** (Merah & Emas)

Seluruh file aset berada di `public/themes/wedding-walasuji/assets/`.  
Anda bisa mengganti gambar apa saja (`.svg` -> `.png`, `.webp`, atau `.jpg`) cukup dengan:
1. Menaruh file gambar baru Anda ke folder `public/themes/wedding-walasuji/assets/`.
2. Mengubah nama filenya pada array `$slots` di bagian paling atas file [wedding-walasuji.blade.php](file:///c:/laragon/www/exo_expanse/resources/views/livewire/themes/wedding-walasuji.blade.php), atau menimpa file `.svg` bawaan dengan nama yang sama.

| Key di `$slots` | File Bawaan | Lokasi Tampil di Template | Fungsi & Motif | Rasio / Ukuran Rekomendasi |
| :--- | :--- | :--- | :--- | :--- |
| `cover_hero_bg` | `cover-hero-bg.svg` | Cover pembuka & Hero | Latar langit senja merah maroon & ombak laut | `9:16` potret (`900×1600` px) |
| `walasuji_gate_pillar` | `walasuji-gate-pillar.svg` | Sisi kiri & kanan Cover | Pilar gerbang anyaman bambu Walasuji (kanan di-mirror otomatis) | Vertikal tipis `1:6` (`140×840` px), transparan |
| `canopy_arch_top` | `canopy-arch-top.svg` | Bagian atas Cover & Hero | Kanopi ukiran emas & rantai manik gantung | Horizontal lebar `~2.2:1` (`900×410` px), transparan |
| `floral_crest_bottom` | `floral-crest-bottom.svg` | Bagian bawah Cover & Hero | Ornamen bunga & tumpal Bugis lengkung bawah | Horizontal `~2:1` (`900×450` px), transparan |
| `hero_border_frame` | `hero-border-frame.svg` | Bingkai keliling Hero | Bingkai garis emas ramping keliling layar | `9:16` (`900×1600` px), tengah transparan |
| `bosara_emblem` | `bosara-emblem.svg` | Tengah atas Cover & Penutup | Ilustrasi Tudung Saji *Bosara* adat Bugis-Makassar | `1:1` (`400×400` px), transparan |
| `phinisi_hero_ship` | `phinisi-hero-ship.svg` | Tengah layar Hero | Ilustrasi utama Kapal *Phinisi* merah-emas | `4:3` (`640×480` px), transparan |
| `phinisi_horizon` | `phinisi-horizon-silhouette.svg` | Cakrawala laut Cover & Hero | Siluet kapal Phinisi kecil yang meluncur pelan horizontal | `1.5:1` (`180×120` px), transparan |
| `bg_lippa_sabbe` | `bg-lippa-sabbe-tile.svg` | Latar kanvas section terang | Pola kotak tenun sutra *Lippa Sabbe* gading hangat | `1:1` repeatable tile (`160×160` px) |
| `card_songket_grid` | `card-songket-grid.svg` | Permukaan dalam semua kartu oktagon | Pola titik micro-grid songket tipis | `1:1` repeatable tile (`48×48` px) |
| `card_corner_drape` | `card-corner-drape.svg` | Sudut kiri-atas & kanan-bawah kartu | Drapery kain merah-emas & rumbai (otomatis diputar 180° di bawah) | `1:1` (`160×160` px), transparan |
| `card_diamond_jewel` | `card-diamond-jewel.svg` | Atas kartu & node timeline Kisah Cinta | Bros berlian emas 45° dengan batu rubi merah | `1:1` (`64×64` px), transparan |
| `divider_ribbon` | `divider-lippa-sabbe-ribbon.svg` | Di bawah setiap judul section | Pita tenun *Lippa Sabbe* merah-emas horizontal | `10:1` (`600×60` px), transparan |
| `mempelai_backdrop` | `mempelai-sea-backdrop.svg` | Latar penuh section Kedua Mempelai | Latar laut & layar Phinisi merah marun gelap | `1:2` (`900×1800` px) |
| `section_mandala_cap` | `section-mandala-cap.svg` | Transisi atas & bawah Kedua Mempelai | Lengkungan pembatas section gelap ke terang | `2.5:1` (`900×360` px), transparan |
| `walasuji_diamond_frame` | `walasuji-diamond-frame.svg` | Bingkai foto Mempelai Pria & Wanita | Bingkai belah ketupat anyaman Walasuji emas + rumbai | `1:1` (`500×500` px), tengah transparan |
| `default_groom` | `default-couple-groom.svg` | Foto cadangan Mempelai Pria | Siluet mempelai pria jika user belum upload foto | `1:1` (`500×500` px) |
| `default_bride` | `default-couple-bride.svg` | Foto cadangan Mempelai Wanita | Siluet mempelai wanita jika user belum upload foto | `1:1` (`500×500` px) |
| `default_gallery_1` | `default-gallery-1.svg` | Galeri mode Demo #1 | Slide placeholder galeri saat mode demo | `4:3` (`800×600` px) |
| `default_gallery_2` | `default-gallery-2.svg` | Galeri mode Demo #2 | Slide placeholder galeri saat mode demo | `4:3` (`800×600` px) |
| `default_gallery_3` | `default-gallery-3.svg` | Galeri mode Demo #3 | Slide placeholder galeri saat mode demo | `4:3` (`800×600` px) |
| *(Theme Thumbnail)* | `thumbnail.jpg` | Pemilih Tema & OG WhatsApp fallback | Thumbnail kartu tema di halaman pilih tema / Admin (`ThemeSeeder`) | `2:3` (`600×900` px, JPEG `< 60 KB`) |
