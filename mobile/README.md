# Mobile App (Expo + TypeScript)

Fondasi mobile Exo Expanse menggunakan React Native Expo + TypeScript untuk owner/pelanggan mengelola undangan.

## Prinsip Startup

- First open selalu menampilkan halaman dasar/public terlebih dahulu.
- Login/register hanya diminta saat user memilih aksi protected (contoh: pilih tema final atau kelola undangan).
- Flow Hubungkan Perangkat via access code tetap tersedia sebagai jalur owner.
- Detail arsitektur ada di docs/stage-1-authless-owner-flow.md.

## Struktur Dasar

- src/app: komposisi root aplikasi
- src/navigation: stack navigator (public first-open + auth + app)
- src/features/auth: auth choice, login/register, dan koneksi perangkat owner
- src/features/invitations: API contract dan request invitation
- src/services: helper HTTP client
- src/shared: komponen reusable dan design tokens
- src/screens: layar level halaman

## Menjalankan Proyek

```bash
npm install
npx expo start
```

Validasi TypeScript:

```bash
npm run typecheck
```

## Environment

1. Duplikasi .env.example menjadi .env.
2. Isi EXPO_PUBLIC_API_BASE_URL ke API Laravel Anda.

Contoh:

```bash
EXPO_PUBLIC_API_BASE_URL=http://192.168.1.10:8000
```

## Endpoint Laravel Yang Dipakai

- GET /api/invitations/{invitation}/stats
- GET /api/invitations/{invitation}/wishes
- POST /api/invitations/{invitation}/rsvp
- POST /api/invitations/{invitation}/wishes

## Tahapan

- Stage 1: flow authless owner + kontrak token session
- Stage 2: implement API exchange/refresh/revoke owner session
- Stage 3+: implement API dan layar kelola undangan end-to-end
