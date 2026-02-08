---
description: Panduan cara kerja sistem tema undangan, membuat tema baru, dan styling
---

# Sistem Tema Undangan

## Arsitektur Tema

### 1. Struktur File

```
resources/views/
├── components/
│   ├── themes/                      # Tema undangan
│   │   ├── ⚡generic.blade.php      # Tema default
│   │   ├── ⚡royal-gold.blade.php   # Tema Gold mewah
│   │   ├── ⚡floral-romance.blade.php # Tema Rose romantis
│   │   └── ⚡modern-elegance.blade.php # Tema modern
│   └── invitation/
│       └── rsvp-wishes.blade.php    # Component reusable RSVP+Wishes
├── livewire/
│   └── theme-page.blade.php        # Router tema
└── layouts/
    └── invitation-layout.blade.php # Layout dasar undangan
```

### 2. Database Themes

Tema disimpan di tabel `themes` dengan konfigurasi:

| ID | Name | Slug | Primary Color | Font |
|----|------|------|---------------|------|
| 1 | Royal Gold | royal-gold | #C9A227 | Cormorant Garamond |
| 2 | Floral Romance | floral-romance | #E8919B | Playfair Display |
| 3 | Modern Elegance | modern-elegance | #C5A059 | Playfair Display |
| 4 | Classic Minimal | generic | #1f2937 | Playfair Display |
| 5 | Sage Garden | sage-garden | #6b8e6b | Cormorant Garamond |
| 6 | Midnight Elegance | midnight-dark | #f59e0b | Playfair Display |

**Menjalankan Seeder:**
// turbo
```bash
php artisan db:seed --class=ThemeSeeder
```

### 3. Alur Render Tema

1. User mengakses `/i/{slug}?kpd=NamaTamu`
2. Route `invitation.show` → `ThemePage` Livewire component
3. `ThemePage` mengambil tema dari `invitation.theme_id`
4. Render tema menggunakan `<x-dynamic-component :component="'themes.⚡' . $theme->slug" />`

### 3. Model Terkait

- **Invitation** - Data undangan (couple, event, settings)
- **Theme** - Konfigurasi tema (colors, fonts, slug)
- **Guest** - Data tamu undangan
- **Wish** - Ucapan yang dikirim tamu

## Membuat Tema Baru

### Langkah 1: Buat File Template

// turbo
```bash
touch resources/views/components/themes/⚡nama-tema.blade.php
```

### Langkah 2: Struktur Dasar Tema

```blade
<?php

use App\Models\Invitation;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.invitation-layout')]
class extends Component {
    public Invitation $invitation;
    public ?array $metadata = null;
    public ?string $guestName = null;

    public function mount(Invitation $invitation, ?array $metadata = null): void
    {
        $this->invitation = $invitation;
        $this->metadata = $metadata;
        $this->guestName = request('kpd', 'Tamu Undangan');
    }
}; ?>

@section('title', 'Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
<link href="https://fonts.googleapis.com/css2?family=..." rel="stylesheet">
@endpush

@push('styles')
<style>
:root {
    --primary: #yourcolor;
    --secondary: #yourcolor;
}
/* CSS tema */
</style>
@endpush

<div x-data="{
    opened: false,
    playing: false,
    // minimal x-data untuk cover/music
}">
    {{-- COVER --}}
    <div x-show="!opened">
        <!-- Opening cover -->
    </div>

    {{-- MAIN CONTENT --}}
    <main x-show="opened">
        {{-- HERO --}}
        {{-- COUPLE --}}
        {{-- EVENTS --}}
        {{-- COUNTDOWN --}}
        {{-- GALLERY --}}
        
        {{-- RSVP & WISHES - GUNAKAN COMPONENT REUSABLE --}}
        <x-invitation.rsvp-wishes :invitation="$invitation" theme="gold" />
        
        {{-- GIFT --}}
        {{-- FOOTER --}}
    </main>
</div>
```

### Langkah 3: Register Tema di Database

```bash
php artisan tinker
```

```php
App\Models\Theme::create([
    'name' => 'Nama Tema',
    'slug' => 'nama-tema',  // tanpa ⚡, otomatis ditambahkan
    'view_file' => 'nama-tema',
    'thumbnail_url' => '/images/themes/nama-tema.jpg',
    'is_active' => true,
    'is_premium' => false,
    'primary_color' => '#d97706',
    'secondary_color' => '#1f2937',
    'heading_font' => 'Playfair Display',
    'body_font' => 'Inter',
]);
```

## Component RSVP+Wishes

### Cara Penggunaan

```blade
{{-- Theme variants: default, gold, rose, sage, dark --}}
<x-invitation.rsvp-wishes :invitation="$invitation" theme="gold" />
```

### Theme Variants

| Theme     | Warna Utama          | Cocok Untuk           |
|-----------|---------------------|----------------------|
| `default` | Slate (Abu)         | Generic themes       |
| `gold`    | Amber/Gold          | Royal, Mewah         |
| `rose`    | Rose Pink           | Romantis, Floral     |
| `sage`    | Emerald/Hijau       | Natural, Garden      |
| `dark`    | Slate 900 + Gold    | Dark mode themes     |

### Fitur Component

- ✅ Form RSVP + Ucapan gabungan
- ✅ Statistik (total ucapan, tamu hadir)
- ✅ Submit tanpa reload (Alpine.js + Fetch API)
- ✅ Animasi loading dan success
- ✅ Tampilkan daftar ucapan real-time

## API Endpoints

Component RSVP+Wishes menggunakan API berikut:

```
POST   /api/invitations/{id}/rsvp    - Submit RSVP
POST   /api/invitations/{id}/wishes  - Submit ucapan
GET    /api/invitations/{id}/wishes  - Load ucapan
GET    /api/invitations/{id}/stats   - Load statistik
```

## Tips Styling Tema

1. **Gunakan CSS Variables** untuk warna agar mudah dikustomisasi
2. **Minimal x-data** - Logic RSVP sudah di dalam component
3. **Mobile-first** - Desain untuk mobile terlebih dahulu
4. **Google Fonts** - Import fonts di `@push('fonts')`
5. **Animasi halus** - Gunakan transitions dan animations
