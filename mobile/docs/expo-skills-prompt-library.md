# Expo Skills Prompt Library untuk ExoInvite Mobile

Dokumen ini berisi prompt siap pakai untuk Copilot Chat agar pekerjaan di project Expo lebih cepat: redesign UI, fitur baru, refactor API, upgrade SDK, sampai deployment.

## Cara Pakai Cepat

1. Buka Copilot Chat.
2. Ketik slash command skill di awal prompt, contoh: /building-native-ui.
3. Sebutkan file target yang ingin diubah.
4. Tambahkan batasan yang Anda inginkan (jangan ubah API, pertahankan alur, dll).
5. Minta Copilot untuk edit langsung file, bukan hanya memberi saran.

## Template Universal

Gunakan template ini untuk kasus apa pun:

/skill-name Kerjakan [tujuan] pada [file target], pertahankan [batasan], implementasikan langsung, lalu jelaskan perubahan dan alasan singkat.

Contoh:

/building-native-ui Redesain src/features/auth/LoginScreen.tsx agar konsisten dengan stitch/login_to_exoinvite/code.html, pertahankan logic submit dan validasi yang sudah ada, edit langsung file terkait.

## A. Prompt UI dan Redesign

1. /building-native-ui Redesain src/features/auth/LoginScreen.tsx agar visualnya konsisten dengan stitch/login_to_exoinvite/code.html, pertahankan flow login yang ada dan aksesibilitas form.
2. /building-native-ui Redesain src/features/auth/RegisterScreen.tsx mengikuti stitch/registration_screen/code.html dengan hierarchy tipografi lebih jelas, spacing rapi, dan CTA utama lebih kuat.
3. /building-native-ui Samakan gaya visual src/features/auth/AuthChoiceScreen.tsx dengan stitch/welcome_to_exoinvite/code.html, pertahankan alur tombol Continue as Guest dan social sign-in.
4. /building-native-ui Rapikan konsistensi antar auth screens berdasarkan AUTH_SCREENS_INCONSISTENCIES.md, update Login, Register, AuthChoice, dan ConnectDevice.
5. /building-native-ui Perbaiki layout src/screens/WelcomeScreen.tsx agar responsif di layar kecil, aman terhadap notch/safe-area, dan tidak ada clipping di tombol bawah.
6. /building-native-ui Redesign src/screens/HomeScreen.tsx sesuai referensi stitch/theme_catalog/code.html dengan kartu tema yang lebih clean dan label premium/free yang konsisten.
7. /building-native-ui Rapikan src/shared/components/Navbar.tsx agar skala ikon, teks, dan tinggi bar konsisten di Android dan iOS.
8. /building-native-ui Tingkatkan ScreenContainer di src/shared/components/ScreenContainer.tsx agar spacing default antar layar seragam dan aman untuk keyboard.

### Urutan Eksekusi Aman (Anti Konflik) untuk Migrasi ScreenContainer

Jalankan prompt berikut secara berurutan. Jangan lompat tahap agar tidak terjadi konflik style/layout antar file.

1. /building-native-ui Finalkan src/shared/components/ScreenContainer.tsx sebagai fondasi spacing global (padding responsive, max content width, keyboard-safe behavior), jangan ubah logic business layer.
2. /building-native-ui Migrasikan src/features/auth/LoginScreen.tsx dan src/features/auth/RegisterScreen.tsx agar memakai ScreenContainer, hapus padding duplikat per-screen, pertahankan flow submit, validasi, dan navigation.
3. /building-native-ui Migrasikan src/features/auth/AuthChoiceScreen.tsx dan src/features/auth/ConnectDeviceScreen.tsx agar memakai ScreenContainer dengan ritme spacing yang sama seperti Login/Register, jangan ubah route contract.
4. /building-native-ui Migrasikan src/screens/WelcomeScreen.tsx agar memakai ScreenContainer, pastikan aman notch/safe-area dan tombol bawah tidak clipping.
5. /building-native-ui Migrasikan src/screens/ProfileScreen.tsx agar memakai ScreenContainer, seragamkan spacing section dan pastikan area konten aman saat keyboard tampil.
6. /building-native-ui Lakukan pass cleanup semua file yang sudah dimigrasikan: rapikan style yang redundan, samakan contentGap, verifikasi keyboard behavior iOS/Android, dan ringkas delta akhir per file.

Tips anti konflik saat eksekusi:

1. Kerjakan satu tahap sampai selesai sebelum lanjut tahap berikutnya.
2. Batasi edit hanya pada file tahap aktif.
3. Setelah tiap tahap, minta validasi error editor dulu baru lanjut.
4. Jangan refactor naming besar-besaran di tengah migrasi layout.
5. Jika ada perubahan fondasi di ScreenContainer, ulang cek semua screen yang sudah dimigrasikan.


9. /building-native-ui Harmonisasi typography dengan Plus Jakarta Sans dan Manrope memakai src/shared/theme/fonts.ts lalu terapkan ke auth dan home screens.
10. /building-native-ui Audit dan refactor warna di src/shared/theme/colors.ts dan src/constants/theme.ts agar kontras teks dan background memenuhi keterbacaan.

## B. Prompt Data Fetching, Auth, dan API

1. /native-data-fetching Refactor src/services/httpClient.ts agar punya timeout, error mapping, dan retry ringan untuk request idempotent.
2. /native-data-fetching Perkuat src/features/auth/auth.api.ts dengan handling error backend yang konsisten untuk login, register, dan refresh token.
3. /native-data-fetching Implement request cancellation di Login dan Register agar submit berulang tidak menyebabkan race condition.
4. /native-data-fetching Rapikan src/features/auth/AuthContext.tsx supaya session bootstrap saat app start lebih aman dan tidak flicker state auth.
5. /native-data-fetching Audit storage token di src/features/auth/auth.storage.ts agar aman untuk native dan fallback web tetap stabil.
6. /native-data-fetching Tambahkan guard ketika EXPO_PUBLIC_API_BASE_URL invalid di src/config/env.ts dan berikan error message developer-friendly.
7. /native-data-fetching Refactor src/features/invitations/invitation.api.ts dengan typing response yang ketat dan fallback jika jaringan lambat.
8. /native-data-fetching Buat pola standar response parser di layer API agar semua endpoint memproses error shape backend dengan konsisten.
9. /native-data-fetching Implement mekanisme auto-refresh token yang aman dari loop di AuthContext dan httpClient.
10. /native-data-fetching Tambahkan logging debug request-response terkontrol untuk development mode tanpa membocorkan token.

## C. Prompt Navigasi, Struktur App, dan UX Flow

1. /building-native-ui Review dan rapikan flow navigasi auth ke main app di src/navigation/RootNavigator.tsx agar transisi antar screen lebih mulus.
2. /building-native-ui Audit screen order dan route behavior di src/navigation/types.ts agar typing navigation lebih aman.
3. /building-native-ui Refactor src/screens/ProfileScreen.tsx agar pola layout, header, dan spacing konsisten dengan Home dan Welcome.
4. /building-native-ui Perbaiki src/screens/ThemePreviewScreen.tsx untuk pengalaman preview yang fokus, minim distraksi, dan CTA jelas.
5. /building-native-ui Tingkatkan UX src/screens/UndanganScreen.tsx agar informasi inti cepat terlihat di atas fold.

## D. Prompt Fitur Eksperimental dan Integrasi

1. /use-dom Buat prototype komponen preview undangan berbasis web di dalam app dan integrasikan ke src/screens/ThemePreviewScreen.tsx.
2. /use-dom Buat komponen rich content editor sederhana berbasis DOM untuk kebutuhan konten undangan, lalu sambungkan ke screen yang relevan.
3. /expo-tailwind-setup Setup Tailwind v4 + NativeWind v5 untuk project ini, lalu migrasikan satu layar contoh yaitu src/features/auth/LoginScreen.tsx.
4. /expo-module Buat local Expo module sederhana untuk helper device info yang dibutuhkan ConnectDeviceScreen.

## E. Prompt Build, Dev Client, Deployment, dan CI/CD

1. /expo-dev-client Siapkan profile development build terbaik untuk project ini berdasarkan app.json dan package.json, lalu berikan command build iOS dan Android.
2. /expo-deployment Susun checklist rilis TestFlight dan Play Store untuk app ini, termasuk credential, versioning, dan verifikasi pasca submit.
3. /expo-deployment Buat strategi release bertahap (internal test, beta, production) yang cocok untuk tim kecil.
4. /expo-cicd-workflows Buat workflow EAS untuk build preview setiap PR dan release saat push tag versi.
5. /expo-cicd-workflows Buat workflow validasi dasar sebelum build (typecheck, lint bila ada, dan pemeriksaan env).

## F. Prompt Upgrade dan Maintenance

1. /upgrading-expo Audit package.json project mobile, rekomendasikan upgrade path Expo SDK paling aman beserta risikonya.
2. /upgrading-expo Eksekusi upgrade dependency Expo secara bertahap, perbaiki incompatibility, dan pastikan project tetap jalan.
3. /upgrading-expo Evaluasi kesiapan migrasi ke praktik terbaru Expo untuk navigation, media, dan caching.
4. /upgrading-expo Buat daftar housekeeping pasca-upgrade khusus project ini agar technical debt berkurang.

## G. Prompt Kombinasi Sprint Cepat

1. /building-native-ui lalu /native-data-fetching Redesain Login dan Register sekaligus rapikan error handling auth tanpa mengubah kontrak API backend.
2. /building-native-ui lalu /expo-tailwind-setup Bangun fondasi design system dulu, lalu migrasikan auth flow sebagai pilot.
3. /native-data-fetching lalu /expo-cicd-workflows Setelah API layer stabil, buat workflow CI agar regresi jaringan cepat terdeteksi.
4. /upgrading-expo lalu /expo-dev-client Upgrade SDK lalu siapkan dev client agar pengujian native fitur tetap lancar.

## Prompt Prioritas Tinggi untuk Kondisi Saat Ini

1. /building-native-ui Rapikan semua auth screens sesuai AUTH_SCREENS_INCONSISTENCIES.md dan referensi stitch, fokus konsistensi visual dan UX.
2. /native-data-fetching Harden auth stack: auth.api, auth.storage, AuthContext, dan httpClient agar tahan terhadap network error nyata.
3. /building-native-ui Redesign HomeScreen katalog tema agar visual premium/free lebih jelas dan scanning list lebih cepat.
4. /expo-dev-client Pastikan setup dev build siap untuk test native changes tanpa mengganggu alur Expo Go.
5. /expo-cicd-workflows Siapkan pipeline preview build per PR untuk mempercepat review UI dan flow.

## Tips Menulis Prompt yang Bagus

1. Selalu sebut file target.
2. Nyatakan apa yang harus dipertahankan.
3. Minta implementasi langsung, bukan pseudo-code.
4. Minta ringkasan perubahan setelah edit.
5. Untuk pekerjaan besar, minta dikerjakan bertahap per file.

---

Update terakhir: 2026-04-13
Lokasi skill: .github/skills
