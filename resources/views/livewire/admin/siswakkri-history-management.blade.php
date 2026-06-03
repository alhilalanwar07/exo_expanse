<div x-data="{ toast: { show: false, message: '', type: 'success' } }"
     x-on:toast.window="toast.message = $event.detail.message; toast.type = $event.detail.type; toast.show = true; setTimeout(() => toast.show = false, 3000)">

    <x-toast-notification />

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Riwayat Input Siswakkri</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Data riwayat dari form nama, akun medsos, dan usia.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button wire:click="export('csv')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition-colors">
                Export CSV
            </button>
            <button wire:click="export('xls')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-xl transition-colors">
                Export Excel
            </button>
        </div>
    </div>

    <div class="mb-4 text-xs text-slate-500 dark:text-slate-400">
        Total riwayat tersimpan: <span class="font-semibold">{{ number_format($totalRows) }}</span>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">
        <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative max-w-md w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl leading-5 bg-white dark:bg-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-colors text-slate-800 dark:text-slate-200" placeholder="Cari nama, akun, atau IP...">
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                <select wire:model.live="platformFilter" class="border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">
                    <option value="">Semua Platform</option>
                    @foreach($platforms as $platform)
                        <option value="{{ $platform }}">{{ strtoupper($platform) }}</option>
                    @endforeach
                </select>

                <select wire:model.live="statusFilter" class="border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">
                    <option value="">Semua Status</option>
                    <option value="new">Data baru</option>
                    <option value="old">Data lama</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Medsos</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Usia</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">IP</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($historyRows as $row)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $row->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-900 dark:text-white">{{ strtoupper($row->social_platform) }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $row->social_account }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900 dark:text-white">{{ $row->age }}</td>
                            <td class="px-6 py-4">
                                @if($row->replaced_previous)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Ganti data lama</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Data baru</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $row->submitted_at?->format('d M Y H:i:s') ?? '-' }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">{{ $row->submitted_from_ip ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    wire:click="deleteHistory({{ $row->id }})"
                                    wire:confirm="Yakin ingin menghapus riwayat ini?"
                                    class="inline-flex items-center px-3 py-1.5 border border-rose-200 dark:border-rose-800/60 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 transition-all"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat input.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($historyRows->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                {{ $historyRows->links() }}
            </div>
        @endif
    </div>
</div>
