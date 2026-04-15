<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Website Analytics</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Real-time visitor statistics and engagement metrics</p>
        </div>

        <!-- Date Range Filter -->
        <div class="mb-6 flex items-center gap-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date Range:</label>
            <select 
                wire:model.live="dateRange"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            >
                @foreach($dateRangeOptions as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <!-- Key Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Visitors Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Visitors</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ number_format($stats['totalVisitors']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM15 20h-2m0 0h-2m2 0a3 3 0 013-3h2.236a3 3 0 013 3v2a3 3 0 01-3 3h-2.236a3 3 0 01-3-3v-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Page Views Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Page Views</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ number_format($stats['totalPageViews']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Avg Page Views per Visitor Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Page Views/Visitor</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ number_format($stats['avgPageViews'], 2) }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Visitors Trend Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Visitors & Page Views Trend</h2>
                @if(!empty($visitorsData['labels']))
                    <div class="relative h-80">
                        <canvas id="visitorsChart"></canvas>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const ctx = document.getElementById('visitorsChart')?.getContext('2d');
                            if (!ctx) return;
                            
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: @json($visitorsData['labels']),
                                    datasets: [
                                        {
                                            label: 'Visitors',
                                            data: @json($visitorsData['visitors']),
                                            borderColor: '#3b82f6',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            tension: 0.4,
                                            fill: true,
                                            pointBackgroundColor: '#3b82f6',
                                            pointBorderColor: '#fff',
                                            pointRadius: 5,
                                            pointHoverRadius: 7,
                                        },
                                        {
                                            label: 'Page Views',
                                            data: @json($visitorsData['pageViews']),
                                            borderColor: '#10b981',
                                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                            tension: 0.4,
                                            fill: true,
                                            pointBackgroundColor: '#10b981',
                                            pointBorderColor: '#fff',
                                            pointRadius: 5,
                                            pointHoverRadius: 7,
                                        },
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        },
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            grid: {
                                                color: 'rgba(0, 0, 0, 0.1)',
                                            },
                                        },
                                    },
                                },
                            });
                        });
                    </script>
                @else
                    <div class="h-80 flex items-center justify-center text-gray-500 dark:text-gray-400">
                        No data available
                    </div>
                @endif
            </div>

            <!-- Top Pages -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Pages</h2>
                @if(!empty($pageViews))
                    <div class="space-y-4 max-h-80 overflow-y-auto">
                        @foreach($pageViews as $index => $page)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $index + 1 }}</span>
                                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $page['url'] }}</span>
                                    </div>
                                    <div class="mt-1 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div 
                                            class="bg-blue-600 h-2 rounded-full"
                                            style="width: {{ max(($page['views'] / $maxPageViews) * 100, 10) }}%"
                                        ></div>
                                    </div>
                                </div>
                                <span class="ml-4 text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($page['views']) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-80 flex items-center justify-center text-gray-500 dark:text-gray-400">
                        No data available
                    </div>
                @endif
            </div>
        </div>

        <!-- Device & Browser Analysis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Browsers -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Browsers</h2>
                @if(!empty($browsers))
                    <div class="space-y-3">
                        @foreach($browsers as $browser)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $browser['name'] }}</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                                        <div 
                                            class="h-2 bg-blue-500 rounded-full"
                                            style="width: {{ max(($browser['users'] / $maxBrowserUsers) * 100, 5) }}%"
                                        ></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white w-12 text-right">{{ number_format($browser['users']) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center justify-center text-gray-500 dark:text-gray-400 h-40">
                        No data available
                    </div>
                @endif
            </div>

            <!-- Operating Systems -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Operating Systems</h2>
                @if(!empty($operatingSystems))
                    <div class="space-y-3">
                        @foreach($operatingSystems as $os)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $os['name'] }}</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                                        <div 
                                            class="h-2 bg-green-500 rounded-full"
                                            style="width: {{ max(($os['users'] / $maxOSUsers) * 100, 5) }}%"
                                        ></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white w-12 text-right">{{ number_format($os['users']) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center justify-center text-gray-500 dark:text-gray-400 h-40">
                        No data available
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-blue-800 dark:text-blue-300">
                    <strong>Real-time Analytics:</strong> Data is fetched from Google Analytics 4. 
                    @if(empty(env('GOOGLE_ANALYTICS_PROPERTY_ID')))
                        ⚠️ <strong>Property ID not configured.</strong> Please set <code>GOOGLE_ANALYTICS_PROPERTY_ID</code> in your .env file.
                    @else
                        Data may be empty if no visitors have accessed your website yet, or if GA4 tracking has not been active. GA4 data typically appears within 24-48 hours of setup.
                    @endif
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
</div>
