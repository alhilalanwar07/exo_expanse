<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aktivasi Berhasil - ExoInvite</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=catchy-mager:400|cormorant-garamond:400,500,600,700|great-vibes:400|outfit:300,400,500,600,700,800|playfair-display:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-outfit antialiased">
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-b from-[#fdf8ef] via-[#fffdf8] to-[#f3e8d3] py-12 px-4 sm:px-6 lg:px-8">
        <img src="{{ asset('assets/themes/bunga_biru_putih_pinggir_atas.png') }}" alt="Ornamen" class="pointer-events-none absolute top-0 right-0 w-52 sm:w-80 opacity-80" />
        <img src="{{ asset('assets/themes/bunga_ungu_putih_pinggir_bawah.png') }}" alt="Ornamen" class="pointer-events-none absolute bottom-0 left-0 w-52 sm:w-80 opacity-75" />
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_18%_20%,rgba(197,150,79,0.2),transparent_42%),radial-gradient(circle_at_80%_82%,rgba(149,105,59,0.15),transparent_38%)]"></div>

        <div class="relative w-full max-w-lg mx-auto">
            <div class="rounded-[28px] border border-[#e8d9be] bg-white/90 backdrop-blur-xl p-8 md:p-10 shadow-[0_24px_60px_-24px_rgba(119,77,31,0.45)] text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 border border-emerald-300">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <p class="mt-4 text-sm font-medium tracking-[0.2em] uppercase text-[#9a6f3f]">Account Activated</p>
                <h1 class="mt-3 text-3xl sm:text-4xl font-semibold text-[#4b2f18] font-playfair-display">Aktivasi Berhasil</h1>
                <p class="mt-3 text-[#6b5338] leading-relaxed">
                    Akun Anda sudah aktif. Sekarang Anda bisa login dan lanjut membuat undangan digital yang indah.
                </p>

                <div class="mt-7 rounded-2xl border border-[#ecdab7] bg-[#fffaf2] p-4 text-left">
                    <p class="text-sm font-semibold text-[#6d5132]">Langkah selanjutnya:</p>
                    <ul class="mt-2 text-sm text-[#8e7455] list-disc list-inside space-y-1">
                        <li>Login menggunakan email yang sudah diaktivasi.</li>
                        <li>Buat undangan pertama Anda.</li>
                        <li>Pilih tema dan bagikan ke tamu dengan cepat.</li>
                    </ul>
                </div>

                <div class="mt-8 flex flex-col gap-3">
                    <a
                        href="{{ route('login') }}"
                        class="w-full py-3.5 rounded-xl text-white font-semibold bg-gradient-to-r from-[#b17a3d] via-[#c9964f] to-[#8e5d2a] hover:opacity-95 transition-all"
                    >
                        Lanjut ke Login
                    </a>
                    <a
                        href="{{ route('home') }}"
                        class="w-full py-3.5 rounded-xl border border-[#d8c4a2] text-[#7a5a36] font-medium hover:bg-[#faf3e7] transition-all"
                    >
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

            <p class="mt-8 text-center text-xs text-[#9f8462]">
                &copy; {{ date('Y') }} ExoInvite. Crafted with elegance.
            </p>
        </div>
    </div>
</body>
</html>
