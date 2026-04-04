<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email - ExoInvite</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=catchy-mager:400|cormorant-garamond:400,500,600,700|great-vibes:400|outfit:300,400,500,600,700,800|playfair-display:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-outfit antialiased">
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-b from-[#fdf8ef] via-[#fffdf8] to-[#f3e8d3] py-12 px-4 sm:px-6 lg:px-8">
        <img src="{{ asset('assets/themes/bunga_biru_putih_pinggir_atas.png') }}" alt="Ornamen" class="pointer-events-none absolute top-0 right-0 w-52 sm:w-80 opacity-80" />
        <img src="{{ asset('assets/themes/bunga_ungu_putih_pinggir_bawah.png') }}" alt="Ornamen" class="pointer-events-none absolute bottom-0 left-0 w-52 sm:w-80 opacity-75" />
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_18%_20%,rgba(197,150,79,0.2),transparent_42%),radial-gradient(circle_at_80%_82%,rgba(149,105,59,0.15),transparent_38%)]"></div>

        <div class="relative w-full max-w-xl mx-auto">
            <div class="text-center mb-8">
                <x-logo variant="full" class="justify-center" />
                <p class="mt-2 text-sm font-medium tracking-[0.2em] uppercase text-[#9a6f3f]">Email Activation</p>
                <h1 class="mt-4 text-3xl sm:text-4xl font-semibold text-[#4b2f18] font-playfair-display">Cek Inbox Anda</h1>
            </div>

            <div class="rounded-[28px] border border-[#e8d9be] bg-white/90 backdrop-blur-xl p-8 shadow-[0_24px_60px_-24px_rgba(119,77,31,0.45)]">
                <p class="text-[#6b5338] leading-relaxed">
                    Kami sudah mengirim link aktivasi ke
                    <span class="font-semibold text-[#9a6f3f]">{{ $email ?? 'email Anda' }}</span>.
                    Setelah aktivasi, Anda bisa login dan mulai mengelola undangan digital.
                </p>

                <div class="mt-5 rounded-2xl border border-[#ecdab7] bg-[#fffaf2] p-4">
                    <p class="text-sm text-[#7f6749] font-medium">Tips cepat:</p>
                    <ul class="mt-2 text-sm text-[#8e7455] space-y-1 list-disc list-inside">
                        <li>Cek folder inbox utama lalu folder spam/promosi.</li>
                        <li>Gunakan email yang sama saat login di mobile.</li>
                        <li>Link aktivasi bersifat sementara.</li>
                    </ul>
                </div>

                @if(session('status') === 'verification-link-sent')
                    <div class="mt-5 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        Link aktivasi baru sudah kami kirim. Silakan cek email Anda kembali.
                    </div>
                @endif

                <div class="mt-7 flex flex-col sm:flex-row gap-3">
                    <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
                        @csrf
                        <button
                            type="submit"
                            class="w-full py-3.5 rounded-xl text-white font-semibold bg-gradient-to-r from-[#b17a3d] via-[#c9964f] to-[#8e5d2a] hover:opacity-95 transition-all"
                        >
                            Kirim Ulang Email Aktivasi
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="sm:w-40">
                        @csrf
                        <button
                            type="submit"
                            class="w-full py-3.5 rounded-xl border border-[#d8c4a2] text-[#7a5a36] font-medium hover:bg-[#faf3e7] transition-all"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <p class="mt-8 text-center text-xs text-[#9f8462]">
                &copy; {{ date('Y') }} ExoInvite. Crafted with elegance.
            </p>
        </div>
    </div>
</body>
</html>
