@php
    $studentNames = [
        'AQILAH AQSHA PUTRI',
        'SITI KHUMAYRAH',
        'ZAHWA ALYA NISYA',
        'MUZAKKIR',
        'GAZALI RAHMAT MADJID',
        'ALIF MAULANA QADRI',
        'ELZA MAHARANI',
        'ALMIRA DIRGAHAYU',
        'FAJRIN FIL ARDI',
        'ANDI AFRIANSYAH',
        'SYAHRUL RAHMAN',
        'AKSAN',
        'ALFATIH MAULIDIYAH',
        'HASNIAR',
        'NURVADILLA',
        'MUJAHIDIN',
        'SARTIKA',
        'MUH.IQBAL MARUYAMA',
        'TASYA APRILIA',
        'SYAHRA SALSABILA',
        'HAERUL FURQAN',
        'AURA NUR ZALZABILA',
        'MUH. ANUGRAH. S',
        'ARYAGUNA',
        'ZASKIA NURLISDA',
        'RISKY DWI PUTRA MADJID',
        'MUHAMMAD ALFATH GUNAWAN',
        'AHMAD',
        'NURPATIA',
        'ALFITRA',
        'MUH IDRUS',
        'IZZA ARNIANTI',
        'MARSHELA IKHA PRATIWI',
        'AIMAN PRATAMA A.YUSUF',
        'ARZITA PUTRI',
        'AMELIA NIRWANA',
        'RIFAT ADIAWAN',
        'NUR RAYA SAFITRI',
        'FARRAS AL- HAFIZH',
        'SABRINA',
        'FAKHIRA AULIA RAMADANI',
        'MUH.PUTRA RAMADHAN',
        'HASNIDAR',
        'AKIFAH SYAHIRA',
        'YUNIAR FIRDAYANTI',
        'ZALZABILA RAMADANI.A',
        'AISYAH KHAIRUN NISA',
        'ANDIKA SAPUTRA',
        'ASYIFA SAFITRI AHMAD',
        'NADILA ZAKYYA',
        'MUH. AMIRUL AL DZIKRI. B',
        'SASKIA IMELDA ARSYAD',
        'ALDA MARINTIN',
        'MUH.AL-FADHIL WIJAYA',
        'SABRINI',
        'REZA HERLAMBANG',
        'MUH IBNU AFDAL',
        'AMIRA AZZAHRA',
        'RAEHANUM',
        'DIVA NOVIANTI',
        'AYU LESTARI',
        'NAHARUDDIN',
        'AURA QUEENSYAH',
        'RASMIZAL',
        'MUH.ILHAM',
        'MUH. SHAFWAN KHAIRULLAH',
        'JENOANSYAH',
        'RIPKI APRIAN',
        'AISYAH',
        'AHMAD FAUZAN',
        'MARWAH',
        'ALVIAN',
        'SHELA ZHULFAIDHA',
        'SHELA ZHULFAEIDHA',
        'ANDI DZAKI',
        'SULKIFLI',
        'ANIZA AMELIA',
        'GILANG TRI UTOMO',
        'AURA KASIH. S',
        'MUH. ANDHYN JIBRAN',
        'MUH.ANDHYN JIBRAN',
        'MUH RIFKI ADITYA',
        'ANUGERAH SAPUTRA',
        'RUSMAN JHUMAEDHYL',
        'YELSA SAPITRI',
        'ANUGERAH SAPUTO',
        'MUH.ADIL FAHRIL',
        'ARIEL',
        'MUH ADIL FAHRIL',
    ];

    $socialPlatforms = [
        'instagram' => 'Instagram',
        'tiktok' => 'TikTok',
        'facebook' => 'Facebook',
    ];

    $prefillByName = [
        'NURPATIA' => ['platform' => 'instagram', 'account' => '@nurfatia_02'],
        'MUH IBNU AFDAL' => ['age' => 16],
        'SHELA ZHULFAEIDHA' => ['platform' => 'instagram', 'account' => 'shela', 'age' => 16],
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Data Medsos</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <style>
        :root {
            --ink: #0f172a;
            --ink-soft: #334155;
            --paper: #f8fafc;
            --panel: #ffffff;
            --line: #dbe3ee;
            --brand: #0ea5a4;
            --brand-strong: #0f766e;
            --accent: #f97316;
            --danger: #b91c1c;
            --success: #065f46;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(70rem 35rem at 95% -5%, rgba(14, 165, 164, 0.20), transparent 50%),
                radial-gradient(60rem 30rem at -5% 100%, rgba(249, 115, 22, 0.18), transparent 55%),
                linear-gradient(170deg, #f8fafc 0%, #eff6ff 48%, #fef3c7 100%);
            padding: 32px 16px;
        }

        .page {
            width: min(880px, 100%);
            margin: 0 auto;
            display: grid;
            gap: 16px;
        }

        .hero {
            padding: 20px;
            border: 1px solid rgba(15, 118, 110, 0.15);
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.95), rgba(236, 253, 245, 0.90));
            border-radius: 18px;
            box-shadow: 0 12px 45px rgba(14, 116, 144, 0.08);
            animation: slide-down .45s ease;
        }

        .hero h1 {
            margin: 0;
            font-size: clamp(1.25rem, 3vw, 2rem);
            line-height: 1.25;
        }

        .hero p {
            margin: 6px 0 0;
            color: var(--ink-soft);
            font-size: 0.95rem;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.06);
            animation: slide-up .45s ease;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field.span-2 {
            grid-column: span 2;
        }

        .label {
            font-weight: 700;
            color: #0b253c;
            font-size: 0.92rem;
        }

        input,
        select {
            width: 100%;
            min-height: 52px;
            border: 1px solid #c9d4e2;
            border-radius: 12px;
            background: #fff;
            color: var(--ink);
            font: inherit;
            font-size: 0.95rem;
            padding: 0 14px;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        input:focus,
        select:focus {
            border-color: #0ea5a4;
            box-shadow: 0 0 0 4px rgba(14, 165, 164, 0.18);
        }

        input:disabled {
            background: #eef2f7;
            color: #64748b;
            cursor: not-allowed;
        }

        .hint {
            margin: 0;
            color: #556780;
            font-size: 0.82rem;
            line-height: 1.4;
        }

        .actions {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        button {
            border: 0;
            border-radius: 12px;
            min-height: 48px;
            padding: 0 16px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: transform .15s ease, filter .15s ease;
        }

        button:hover {
            transform: translateY(-1px);
            filter: brightness(0.98);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-strong));
            color: #fff;
            box-shadow: 0 10px 24px rgba(13, 148, 136, 0.28);
        }

        .btn-muted {
            background: #eef2f7;
            color: #0f172a;
            border: 1px solid #d9e2ee;
        }

        .result {
            border-radius: 16px;
            padding: 16px;
            border: 1px solid rgba(2, 132, 199, 0.28);
            background: linear-gradient(125deg, rgba(240, 249, 255, 0.95), rgba(224, 242, 254, 0.90));
            display: none;
            gap: 10px;
        }

        .result.visible {
            display: grid;
            animation: fade-in .25s ease;
        }

        .result h2 {
            margin: 0;
            font-size: 1rem;
            color: #0c4a6e;
        }

        .result pre {
            margin: 0;
            padding: 12px;
            border-radius: 10px;
            border: 1px dashed #7dd3fc;
            background: #ffffff;
            font-family: 'Consolas', 'Courier New', monospace;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .toast {
            position: fixed;
            right: 16px;
            bottom: 16px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.25);
            opacity: 0;
            transform: translateY(8px);
            pointer-events: none;
            transition: all .2s ease;
            z-index: 20;
        }

        .toast.success {
            background: var(--success);
        }

        .toast.error {
            background: var(--danger);
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            min-height: 52px;
            border-radius: 12px;
            border: 1px solid #c9d4e2;
            display: flex;
            align-items: center;
            padding: 0 42px 0 10px;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #0ea5a4;
            box-shadow: 0 0 0 4px rgba(14, 165, 164, 0.18);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.2;
            color: var(--ink);
            padding-left: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 12px;
        }

        .select2-dropdown {
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(14, 116, 144, 0.15);
        }

        .select2-search__field {
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px;
            padding: 8px;
            font-family: inherit;
        }

        .select2-results__option--highlighted.select2-results__option--selectable {
            background: #0ea5a4 !important;
        }

        @media (max-width: 720px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .field.span-2 {
                grid-column: auto;
            }

            .card,
            .hero {
                padding: 16px;
                border-radius: 14px;
            }
        }

        @keyframes slide-down {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="hero">
            <h1>Form Data Nama, Medsos, dan Usia</h1>
            <p>Pilih nama dengan pencarian, lalu isi data anda dengan benar.</p>
        </section>

        <section class="card">
            <form id="socialForm" novalidate>
                <div class="grid">
                    <div class="field span-2">
                        <label class="label" for="name">Nama</label>
                        <select id="name" name="name" required>
                            <option value=""></option>
                            @foreach ($studentNames as $name)
                                <option value="{{ $name }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <p class="hint">Ketik nama pada kolom pencarian untuk mempercepat pemilihan.</p>
                    </div>

                    <div class="field">
                        <label class="label" for="socialPlatform">Platform Medsos</label>
                        <select id="socialPlatform" name="socialPlatform" required>
                            <option value="">Pilih platform dulu</option>
                            @foreach ($socialPlatforms as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label class="label" for="socialAccount">Akun Medsos</label>
                        <input
                            id="socialAccount"
                            name="socialAccount"
                            type="text"
                            maxlength="150"
                            placeholder="Pilih platform medsos dulu"
                            disabled
                            required
                        >
                        <p class="hint" id="accountHint">Setelah platform dipilih, kolom akun akan aktif.</p>
                    </div>

                    <div class="field">
                        <label class="label" for="age">Usia</label>
                        <input
                            id="age"
                            name="age"
                            type="number"
                            min="1"
                            max="120"
                            inputmode="numeric"
                            placeholder="Contoh: 16"
                            required
                        >
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-primary" id="submitButton">Simpan Data</button>
                    <button type="reset" class="btn-muted" id="resetButton">Reset</button>
                </div>
            </form>

            <div class="result" id="resultPanel" aria-live="polite">
                <h2>Ringkasan Data</h2>
                <pre id="resultText"></pre>
                <div class="actions" style="margin-top:0;">
                    <button type="button" class="btn-primary" id="copyButton">Salin Hasil</button>
                </div>
            </div>
        </section>
    </main>

    <div class="toast" id="toast"></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const prefillByName = @json($prefillByName, JSON_UNESCAPED_SLASHES);

        const platformHints = {
            instagram: 'Contoh: @username_ig',
            tiktok: 'Contoh: @username_tiktok',
            facebook: 'Contoh: nama profil atau URL Facebook',
            x: 'Contoh: @username_x',
            youtube: 'Contoh: @channel atau URL channel',
            whatsapp: 'Contoh: 08xxxxxxxxxx',
            telegram: 'Contoh: @username_telegram',
            other: 'Isi akun medsos yang dipakai',
        };

        const form = document.getElementById('socialForm');
        const nameSelect = document.getElementById('name');
        const socialPlatform = document.getElementById('socialPlatform');
        const socialAccount = document.getElementById('socialAccount');
        const ageInput = document.getElementById('age');
        const accountHint = document.getElementById('accountHint');
        const resultPanel = document.getElementById('resultPanel');
        const resultText = document.getElementById('resultText');
        const copyButton = document.getElementById('copyButton');
        const resetButton = document.getElementById('resetButton');
        const submitButton = document.getElementById('submitButton');
        const toast = document.getElementById('toast');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const submitUrl = @json(route('social-form.store'));

        function showToast(message, type = 'success') {
            toast.textContent = message;
            toast.className = `toast ${type} show`;

            window.clearTimeout(showToast.timer);
            showToast.timer = window.setTimeout(() => {
                toast.classList.remove('show');
            }, 1700);
        }

        function syncSocialAccountState() {
            const selectedPlatform = socialPlatform.value;

            if (!selectedPlatform) {
                socialAccount.value = '';
                socialAccount.disabled = true;
                socialAccount.placeholder = 'Pilih platform medsos dulu';
                accountHint.textContent = 'Setelah platform dipilih, kolom akun akan aktif.';
                return;
            }

            socialAccount.disabled = false;
            socialAccount.placeholder = platformHints[selectedPlatform] ?? 'Isi akun medsos';
            accountHint.textContent = socialAccount.placeholder;
        }

        function applyNamePrefill() {
            const selectedName = nameSelect.value;
            const profile = prefillByName[selectedName];

            if (!profile) {
                return;
            }

            if (profile.platform && !socialPlatform.value) {
                socialPlatform.value = profile.platform;
            }

            syncSocialAccountState();

            if (profile.account && !socialAccount.value) {
                socialAccount.value = profile.account;
            }

            if (profile.age && !ageInput.value) {
                ageInput.value = profile.age;
            }
        }

        async function submitToServer(payload) {
            const response = await fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            });

            const responseData = await response.json().catch(() => ({}));

            if (!response.ok) {
                if (responseData.errors) {
                    const firstError = Object.values(responseData.errors)[0]?.[0];
                    throw new Error(firstError ?? 'Validasi gagal.');
                }

                throw new Error(responseData.message ?? 'Gagal menyimpan data.');
            }

            return responseData;
        }

        $(function () {
            $('#name').select2({
                placeholder: 'Cari dan pilih nama',
                allowClear: true,
                width: '100%',
            });

            $('#name').on('change', applyNamePrefill);
        });

        socialPlatform.addEventListener('change', syncSocialAccountState);

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (socialAccount.disabled) {
                showToast('Pilih platform medsos dulu sebelum isi akun.', 'error');
                return;
            }

            const selectedPlatformLabel = socialPlatform.options[socialPlatform.selectedIndex].text;
            const normalizedAccount = socialAccount.value.trim();

            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';

            try {
                const payload = {
                    name: nameSelect.value,
                    social_platform: socialPlatform.value,
                    social_account: normalizedAccount,
                    age: Number(ageInput.value),
                };

                const responseData = await submitToServer(payload);

                const lines = [
                    `Nama: ${nameSelect.value}`,
                    `Platform Medsos: ${selectedPlatformLabel}`,
                    `Akun Medsos: ${normalizedAccount}`,
                    `Usia: ${ageInput.value}`,
                    `Status Simpan: ${responseData.replaced_previous ? 'Data lama dihapus, data baru disimpan' : 'Data baru disimpan'}`,
                ];

                resultText.textContent = lines.join('\n');
                resultPanel.classList.add('visible');
                resultPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                showToast(responseData.message ?? 'Data berhasil disimpan.');
            } catch (error) {
                showToast(error.message ?? 'Terjadi kesalahan saat menyimpan data.', 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Data';
            }
        });

        copyButton.addEventListener('click', async () => {
            const text = resultText.textContent.trim();

            if (!text) {
                showToast('Belum ada data untuk disalin.', 'error');
                return;
            }

            try {
                await navigator.clipboard.writeText(text);
                showToast('Ringkasan berhasil disalin.');
            } catch (error) {
                showToast('Gagal menyalin. Coba salin manual.', 'error');
            }
        });

        resetButton.addEventListener('click', () => {
            window.setTimeout(() => {
                $('#name').val(null).trigger('change');
                resultPanel.classList.remove('visible');
                resultText.textContent = '';
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Data';
                syncSocialAccountState();
            }, 0);
        });

        syncSocialAccountState();
    </script>
</body>
</html>
