{{-- Example: Complete Invitation Template with Theme Customization --}}
{{-- File: resources/views/invitation-example.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invitation->couple_names }} - Undangan Pernikahan</title>

    {{-- Google Fonts dari Theme --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $invitation->getThemeConfig()['googleFontsUrl'] }}" rel="stylesheet">

    {{-- Theme Styles (Dynamic CSS Variables) --}}
    <style>
        {!! $invitation->getThemeStyles() !!}
    </style>

    {{-- Template Styles --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--color-background);
            color: var(--color-text);
            font-family: var(--font-body);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            color: var(--color-heading);
        }

        .accent {
            color: var(--color-accent);
            font-family: var(--font-accent);
        }

        .container {
            max-width: var(--container-max-width);
            margin: 0 auto;
            padding: 0 1rem;
        }

        .section {
            padding: 3rem 0;
        }

        .btn-primary {
            background-color: var(--color-primary);
            color: var(--color-text);
            border: 2px solid var(--color-accent);
            padding: 0.75rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary:hover {
            background-color: var(--color-accent);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .card {
            background-color: var(--color-primary);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin: 1rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--color-accent);
        }

        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, var(--color-accent), transparent);
            margin: 2rem 0;
        }

        /* Custom animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
    </style>
</head>
<body>
    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 z-40" style="background-color: rgba(var(--color-primary), 0.95);">
        <div class="container flex justify-between items-center py-4">
            <h2 class="accent font-bold">Undangan Digital</h2>

            <div class="space-x-6 hidden md:flex">
                <a href="#acara" class="hover:accent transition">Acara</a>
                <a href="#galeri" class="hover:accent transition">Galeri</a>
                <a href="#ucapan" class="hover:accent transition">Ucapan</a>
                <a href="#rsvp" class="hover:accent transition">RSVP</a>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="pt-16">
        {{-- Header/Cover Section --}}
        <section class="section py-20 min-h-screen flex items-center justify-center">
            <div class="container text-center fade-in-up">
                <p class="text-sm tracking-widest accent mb-4">THE WEDDING OF</p>

                <h1 class="text-5xl md:text-6xl font-bold mb-4">
                    {{ $invitation->couple_names }}
                </h1>

                <p class="accent text-2xl md:text-3xl font-light mb-8">
                    Save The Date
                </p>

                <div class="divider"></div>

                <p class="mt-8 text-base">
                    Kami dengan segenap hati mengundang Anda untuk hadir<br>
                    dalam momen istimewa dan penuh berkah dalam hidup kami
                </p>

                <p class="mt-8 accent text-xl">
                    @if($invitation->event_date)
                        {{ \Carbon\Carbon::parse($invitation->event_date)->locale('id')->format('d F Y') }}
                    @endif
                </p>
            </div>
        </section>

        {{-- Event Details Section --}}
        <section id="acara" class="section py-12" style="background: rgba(var(--color-accent), 0.02);">
            <div class="container">
                <h2 class="text-4xl font-bold text-center mb-12 accent">
                    Acara Pernikahan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    {{-- Akad Ceremony --}}
                    <div class="card">
                        <h3 class="text-2xl font-semibold mb-6 accent">Akad Nikah</h3>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm accent font-semibold">HARI & TANGGAL</p>
                                <p class="text-lg mt-1">
                                    @if($invitation->getContentValue('akad_date'))
                                        {{ \Carbon\Carbon::parse($invitation->getContentValue('akad_date'))->locale('id')->format('l, d F Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="text-sm accent font-semibold">WAKTU</p>
                                <p class="text-lg mt-1">
                                    {{ $invitation->getContentValue('akad_time', '-') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm accent font-semibold">LOKASI</p>
                                <p class="text-lg font-semibold mt-1">
                                    {{ $invitation->getContentValue('akad_venue', '-') }}
                                </p>
                                <p class="text-sm mt-2">
                                    {{ $invitation->getContentValue('akad_address', '-') }}
                                </p>
                            </div>
                        </div>

                        @if($invitation->getContentValue('akad_maps_link'))
                            <a href="{{ $invitation->getContentValue('akad_maps_link') }}"
                               target="_blank"
                               class="btn-primary mt-6 block text-center">
                                📍 Buka di Google Maps
                            </a>
                        @endif
                    </div>

                    {{-- Resepsi Ceremony --}}
                    <div class="card">
                        <h3 class="text-2xl font-semibold mb-6 accent">Resepsi</h3>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm accent font-semibold">HARI & TANGGAL</p>
                                <p class="text-lg mt-1">
                                    @if($invitation->getContentValue('resepsi_date'))
                                        {{ \Carbon\Carbon::parse($invitation->getContentValue('resepsi_date'))->locale('id')->format('l, d F Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="text-sm accent font-semibold">WAKTU</p>
                                <p class="text-lg mt-1">
                                    {{ $invitation->getContentValue('resepsi_time', '-') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm accent font-semibold">LOKASI</p>
                                <p class="text-lg font-semibold mt-1">
                                    {{ $invitation->getContentValue('resepsi_venue', '-') }}
                                </p>
                                <p class="text-sm mt-2">
                                    {{ $invitation->getContentValue('resepsi_address', '-') }}
                                </p>
                            </div>
                        </div>

                        @if($invitation->getContentValue('resepsi_maps_link'))
                            <a href="{{ $invitation->getContentValue('resepsi_maps_link') }}"
                               target="_blank"
                               class="btn-primary mt-6 block text-center">
                                📍 Buka di Google Maps
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Countdown Timer --}}
                <div class="mt-12 text-center">
                    <p class="text-sm accent mb-4">HITUNG MUNDUR</p>
                    <div x-data="countdown('{{ $invitation->event_date->toIso8601String() }}')" class="grid grid-cols-4 gap-4 max-w-md mx-auto">
                        <div class="text-center">
                            <p class="text-3xl font-bold" x-text="days">0</p>
                            <p class="text-xs mt-2">HARI</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold" x-text="hours">0</p>
                            <p class="text-xs mt-2">JAM</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold" x-text="minutes">0</p>
                            <p class="text-xs mt-2">MENIT</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold" x-text="seconds">0</p>
                            <p class="text-xs mt-2">DETIK</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Gallery Section --}}
        @if($invitation->photos()->exists())
            <section id="galeri" class="section py-12">
                <div class="container">
                    <h2 class="text-4xl font-bold text-center mb-12 accent">
                        Galeri Foto
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-4xl mx-auto">
                        @foreach($invitation->photos as $photo)
                            <div class="rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                                <img src="{{ asset('storage/' . $photo->path) }}"
                                     alt="Gallery"
                                     class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- RSVP Section --}}
        @if($invitation->rsvp_enabled)
            <section id="rsvp" class="section py-12" style="background: rgba(var(--color-secondary), 0.05);">
                <div class="container">
                    <h2 class="text-4xl font-bold text-center mb-12 accent">
                        Konfirmasi Kehadiran
                    </h2>

                    <div class="max-w-md mx-auto">
                        <livewire:rsvp-form :invitation="$invitation" />
                    </div>
                </div>
            </section>
        @endif

        {{-- Wishes Section --}}
        @if($invitation->wishes_enabled)
            <section id="ucapan" class="section py-12">
                <div class="container">
                    <h2 class="text-4xl font-bold text-center mb-12 accent">
                        Ucapan dan Doa
                    </h2>

                    <div class="max-w-2xl mx-auto">
                        <livewire:wishes-feed :invitation="$invitation" />
                    </div>
                </div>
            </section>
        @endif

        {{-- Footer --}}
        <footer class="py-8 text-center" style="background-color: var(--color-secondary); color: white;">
            <div class="container">
                <p class="mb-2">Terima kasih telah menjadi bagian dari kebahagiaan kami</p>
                <p class="text-sm opacity-75">
                    Dibuat dengan ❤️ menggunakan <strong>Exo-Expanse</strong>
                </p>
            </div>
        </footer>
    </main>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Alpine Components --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', (targetDate) => ({
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,

                init() {
                    this.updateCountdown();
                    setInterval(() => this.updateCountdown(), 1000);
                },

                updateCountdown() {
                    const target = new Date(targetDate).getTime();
                    const now = new Date().getTime();
                    const diff = target - now;

                    if (diff > 0) {
                        this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    }
                }
            }));
        });
    </script>
</body>
</html>
