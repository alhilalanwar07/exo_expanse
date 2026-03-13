{{-- Toast Notification Component --}}
{{-- Usage: Add x-data/x-on on parent div, then @include this component --}}
{{-- Parent div needs: x-data="{ toast: { show: false, message: '', type: 'success' } }" --}}
{{--                   x-on:toast.window="toast.message = $event.detail.message; toast.type = $event.detail.type; toast.show = true; setTimeout(() => toast.show = false, 3000)" --}}
<div x-show="toast.show" x-cloak
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-5 right-5 z-50 w-full max-w-sm overflow-hidden rounded-lg shadow-lg ring-1 ring-black/5 pointer-events-auto"
     :class="{
        'bg-white dark:bg-slate-800': true
     }">
    <div class="p-4">
        <div class="flex items-start">
            <div class="shrink-0">
                <!-- Success Icon -->
                <template x-if="toast.type === 'success'">
                    <svg class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <!-- Error Icon -->
                <template x-if="toast.type === 'error'">
                    <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </template>
                <!-- Info Icon -->
                <template x-if="toast.type === 'info'">
                    <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </template>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-slate-900 dark:text-white"
                   x-text="toast.type === 'success' ? 'Berhasil!' : (toast.type === 'error' ? 'Gagal!' : 'Informasi')"></p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400" x-text="toast.message"></p>
            </div>
            <div class="ml-4 flex shrink-0">
                <button @click="toast.show = false" class="inline-flex rounded-md text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Progress bar -->
    <div class="h-1 w-full"
         :class="{
            'bg-emerald-100 dark:bg-emerald-900/30': toast.type === 'success',
            'bg-red-100 dark:bg-red-900/30': toast.type === 'error',
            'bg-blue-100 dark:bg-blue-900/30': toast.type === 'info'
         }">
        <div class="h-full transition-all duration-[3000ms] ease-linear"
             :class="{
                'bg-emerald-500': toast.type === 'success',
                'bg-red-500': toast.type === 'error',
                'bg-blue-500': toast.type === 'info'
             }"
             :style="toast.show ? 'width: 0%' : 'width: 100%'"
             x-init="$watch('toast.show', val => { if(val) { $nextTick(() => $el.style.width = '0%'); setTimeout(() => $el.style.width = '100%', 50) } })">
        </div>
    </div>
</div>
