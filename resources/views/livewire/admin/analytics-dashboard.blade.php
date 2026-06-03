<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Header -->
    <div class="sm:flex sm:justify-between sm:items-end mb-8 relative">
        <div class="absolute left-0 top-0 -ml-10 -mt-10 w-40 h-40 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
        <div class="absolute right-0 top-0 -mr-10 -mt-10 w-40 h-40 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
        
        <div class="relative">
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300">
                Dashboard Analitik
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Metrik pengunjung & performa undangan Anda secara komplit.</p>
        </div>
        
        <!-- Date Range Filter -->
        <div class="mt-4 sm:mt-0 relative z-10 flex items-center gap-3 bg-white dark:bg-slate-800 p-1.5 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
            <svg class="w-5 h-5 text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <select 
                wire:model.live="dateRange"
                class="bg-transparent border-0 text-sm font-medium text-slate-700 dark:text-slate-300 focus:ring-0 cursor-pointer pr-8"
            >
                @foreach($dateRangeOptions as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($errorMessage)
        <!-- Error Alert -->
        <div class="mb-8 relative overflow-hidden rounded-2xl bg-gradient-to-r from-red-500 to-rose-600 p-1 transform transition-all hover:scale-[1.01]">
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 flex items-start gap-4">
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full flex-shrink-0 animate-pulse">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">API Google Analytics Bermasalah</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">
                        {{ $errorMessage }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Key Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Visitors -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-blue-500/10 dark:bg-blue-500/5 rounded-bl-[100px] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Visitors</p>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white mt-2 font-display">
                        {{ number_format($stats['totalVisitors']) }}
                    </h2>
                </div>
                <div class="p-2.5 bg-blue-50 dark:bg-blue-900/40 rounded-xl border border-blue-100 dark:border-blue-800">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM15 20h-2m0 0h-2m2 0a3 3 0 013-3h2.236a3 3 0 013 3v2a3 3 0 01-3 3h-2.236a3 3 0 01-3-3v-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Page Views -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-500/10 dark:bg-emerald-500/5 rounded-bl-[100px] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Page Views</p>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white mt-2 font-display">
                        {{ number_format($stats['totalPageViews']) }}
                    </h2>
                </div>
                <div class="p-2.5 bg-emerald-50 dark:bg-emerald-900/40 rounded-xl border border-emerald-100 dark:border-emerald-800">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: Avg Views -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-purple-500/10 dark:bg-purple-500/5 rounded-bl-[100px] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Avg Views/Visitor</p>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white mt-2 font-display">
                        {{ number_format($stats['avgPageViews'], 2) }}
                    </h2>
                </div>
                <div class="p-2.5 bg-purple-50 dark:bg-purple-900/40 rounded-xl border border-purple-100 dark:border-purple-800">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Card 4: Avg Duration -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-amber-500/10 dark:bg-amber-500/5 rounded-bl-[100px] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Avg Duration <span class="text-[10px] lowercase normal-case">(mm:ss)</span></p>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white mt-2 font-display">
                        {{ $stats['avgDuration'] }}
                    </h2>
                </div>
                <div class="p-2.5 bg-amber-50 dark:bg-amber-900/40 rounded-xl border border-amber-100 dark:border-amber-800">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Chart -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold border-b-2 border-primary-500 pb-1 text-slate-800 dark:text-white inline-block">Tren Traffic</h2>
        </div>
        
        @if(!empty($visitorsData['labels']))
            <div class="relative h-[350px] w-full" wire:ignore>
                <canvas id="visitorsChart"></canvas>
            </div>
            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('visitorsChart')?.getContext('2d');
                        if (!ctx) return;
                        
                        let gradientVisitors = ctx.createLinearGradient(0, 0, 0, 400);
                        gradientVisitors.addColorStop(0, 'rgba(59, 130, 246, 0.5)');   
                        gradientVisitors.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

                        let gradientViews = ctx.createLinearGradient(0, 0, 0, 400);
                        gradientViews.addColorStop(0, 'rgba(16, 185, 129, 0.3)');   
                        gradientViews.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: @json($visitorsData['labels']),
                                datasets: [
                                    {
                                        label: 'Visitors',
                                        data: @json($visitorsData['visitors']),
                                        borderColor: '#3b82f6',
                                        backgroundColor: gradientVisitors,
                                        borderWidth: 3,
                                        tension: 0.4,
                                        fill: true,
                                        pointBackgroundColor: '#fff',
                                        pointBorderColor: '#3b82f6',
                                        pointBorderWidth: 2,
                                        pointRadius: 4,
                                        pointHoverRadius: 6,
                                    },
                                    {
                                        label: 'Page Views',
                                        data: @json($visitorsData['pageViews']),
                                        borderColor: '#10b981',
                                        backgroundColor: gradientViews,
                                        borderWidth: 2,
                                        borderDash: [5, 5],
                                        tension: 0.4,
                                        fill: true,
                                        pointBackgroundColor: '#fff',
                                        pointBorderColor: '#10b981',
                                        pointBorderWidth: 2,
                                        pointRadius: 4,
                                        pointHoverRadius: 6,
                                    },
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { mode: 'index', intersect: false },
                                plugins: {
                                    legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8, padding: 20, font: { family: "'Outfit', sans-serif", weight: '500' } } },
                                    tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.9)', titleFont: { family: "'Outfit', sans-serif", size: 13 }, bodyFont: { family: "'Outfit', sans-serif", size: 14, weight: 'bold' }, padding: 12, cornerRadius: 8, displayColors: true, usePointStyle: true }
                                },
                                scales: {
                                    x: { grid: { display: false, drawBorder: false }, ticks: { font: { family: "'Outfit', sans-serif" } } },
                                    y: { beginAtZero: true, grid: { color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)', drawBorder: false }, ticks: { font: { family: "'Outfit', sans-serif" }, padding: 10 } },
                                },
                            },
                        });
                    });
                </script>
            @endpush
        @else
            <div class="h-[350px] flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-dashed border-slate-300 dark:border-slate-700">
                <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12V6a1 1 0 011-1h8a1 1 0 011 1v12a1 1 0 01-1 1H8a1 1 0 01-1-1zm0 0v6m0-6H5m2 0V6a1 1 0 011-1h8a1 1 0 011 1v12a1 1 0 01-1 1H8a1 1 0 01-1-1H5M5 21h14M5 3h14"></path>
                </svg>
                <p class="text-slate-500 font-medium tracking-wide">Belum ada data traffic di periode ini</p>
            </div>
        @endif
    </div>

    <!-- 2 Cols: Sources & Pages -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Traffic Sources -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex flex-col h-[380px]">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Sumber Traffic Referensi
            </h2>
            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                @if(!empty($trafficSources))
                    <div class="space-y-4">
                        @foreach($trafficSources as $source)
                            @php
                                $sourceName = $source['name'];
                                if (str_contains($sourceName, '(direct)')) $sourceName = 'Kunjungan Langsung (Direct Link)';
                                if (str_contains($sourceName, 'organic')) $sourceName = 'Pencarian Organik (Google, dll)';
                                if (str_contains($sourceName, 'referral')) $sourceName = 'Klik dari Website Lain (Referral)';
                                if (str_contains(strtolower($sourceName), 'instagram')) $sourceName = 'Instagram';
                                if (str_contains(strtolower($sourceName), 'facebook')) $sourceName = 'Facebook';
                                if (str_contains(strtolower($sourceName), 'whatsapp')) $sourceName = 'WhatsApp';
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate pr-4">{{ $sourceName }}</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($source['users']) }}</span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-amber-400 to-amber-500 h-2 rounded-full" style="width: {{ max(($source['users'] / max($maxTrafficUsers, 1)) * 100, 2) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex items-center justify-center text-slate-400">Data belum tersedia</div>
                @endif
            </div>
        </div>

        <!-- Top Pages -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex flex-col h-[380px]">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Undangan Paling Populer
            </h2>
            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                @if(!empty($pageViews))
                    <div class="space-y-5">
                        @foreach($pageViews as $index => $page)
                            <div class="group">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-slate-100 dark:bg-slate-700 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-full">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex flex-col truncate">
                                            <span class="text-xs text-slate-400 dark:text-slate-500 truncate" title="{{ $page['title'] }}">
                                                {{ \Illuminate\Support\Str::limit($page['title'], 40) }}
                                            </span>
                                            <a href="{{ $page['url'] !== 'N/A' ? url($page['url']) : '#' }}" target="_blank" rel="noopener noreferrer" class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors inline-block" title="Buka tautan: {{ url($page['url'] !== 'N/A' ? $page['url'] : '') }}">
                                                {{ \Illuminate\Support\Str::limit($page['url'], 35) }}
                                            </a>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white ml-2 bg-slate-100 dark:bg-slate-700 px-2.5 py-0.5 rounded-full">{{ number_format($page['views']) }} view</span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5 group-hover:bg-slate-200 transition-colors">
                                    <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ max(($page['views'] / max($maxPageViews, 1)) * 100, 2) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex items-center justify-center text-slate-400">Data belum tersedia</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Geography: Cities & Countries -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Cities -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-base font-bold text-slate-800 dark:text-white mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Kota Asal Tamu
            </h3>
            <div class="space-y-4">
                @forelse($cities as $city)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-600 dark:text-slate-300">{{ $city['name'] }}</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ number_format($city['users']) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                            <div class="bg-rose-500 h-2 rounded-full" style="width: {{ max(($city['users'] / max($maxCityUsers, 1)) * 100, 2) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-slate-400 py-4">Data belum tersedia</div>
                @endforelse
            </div>
        </div>

        <!-- Countries -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-base font-bold text-slate-800 dark:text-white mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.24 7.51c.36-.08.73-.14 1.1-.19.55-.07 1.1-.1 1.66-.1.6 0 1.2.04 1.77.12.5.07 1 .15 1.5.26M19 12h2a1 1 0 011 1v7a1 1 0 01-1 1h-2m-1-11a9 9 0 00-6-8.5V1.5m6 8.5v3m0 0a9 9 0 01-6 8.5v-1m6-7.5H12"></path></svg>
                Negara
            </h3>
            <div class="space-y-4">
                @forelse($countries as $country)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-600 dark:text-slate-300">{{ $country['name'] }}</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ number_format($country['users']) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                            <div class="bg-rose-400 h-2 rounded-full" style="width: {{ max(($country['users'] / max($maxCountryUsers, 1)) * 100, 2) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-slate-400 py-4">Data belum tersedia</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tech Grid (Device, OS, Browser, Events) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        
        <!-- Device Category -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Kategori Perangkat
            </h3>
            <div class="space-y-4">
                @forelse($devices as $device)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-600 dark:text-slate-300 capitalize">{{ $device['name'] }}</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ number_format($device['users']) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                            <div class="bg-pink-500 h-1.5 rounded-full" style="width: {{ max(($device['users'] / max($maxDeviceUsers, 1)) * 100, 2) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-slate-400 mt-2">Belum ada data</div>
                @endforelse
            </div>
        </div>

        <!-- OS -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Sistem Operasi
            </h3>
            <div class="space-y-4">
                @forelse($operatingSystems as $os)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-600 dark:text-slate-300">{{ $os['name'] }}</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ number_format($os['users']) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                            <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ max(($os['users'] / max($maxOSUsers, 1)) * 100, 2) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-slate-400 mt-2">Belum ada data</div>
                @endforelse
            </div>
        </div>

        <!-- Browser -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                Browser Akses
            </h3>
            <div class="space-y-4">
                @forelse($browsers as $browser)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-600 dark:text-slate-300">{{ $browser['name'] }}</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ number_format($browser['users']) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                            <div class="bg-cyan-500 h-1.5 rounded-full" style="width: {{ max(($browser['users'] / max($maxBrowserUsers, 1)) * 100, 2) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-slate-400 mt-2">Belum ada data</div>
                @endforelse
            </div>
        </div>
        
        <!-- Top Events -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                Aksi Tamu (Events)
            </h3>
            <div class="space-y-4">
                @forelse($events as $event)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-600 dark:text-slate-300 truncate pr-2 w-32" title="{{ $event['name'] }}">{{ $event['name'] }}</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ number_format($event['count']) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                            <div class="bg-violet-500 h-1.5 rounded-full" style="width: {{ max(($event['count'] / max($maxEventCount, 1)) * 100, 2) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-slate-400 mt-2">Belum ada aksi terekam</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Info Box Footer -->
    <div class="bg-gradient-to-r from-slate-100 to-white dark:from-slate-800 dark:to-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 w-full">
        <div class="flex items-start gap-4">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400 mt-0.5">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-800 dark:text-white">API Koneksi Aktif</h4>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">
                    Seluruh data ditarik secara dinamis dari Google Analytics 4. Pembaruan data analitik biasanya memerlukan waktu tunda (delay) 24-48 jam pertama untuk sinkronisasi historis sejak ID dipasang. Jika metrik *Aksi Tamu (Events)* memunculkan data aneh, fitur Custom Events mungkin perlu dipasang di frontend web tamu Anda.
                </p>
            </div>
        </div>
    </div>

    <style>
        .font-display { font-family: 'Outfit', sans-serif; letter-spacing: -0.02em; }
        /* Hide scrollbar for a cleaner look in cards */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
    </style>
</div>

