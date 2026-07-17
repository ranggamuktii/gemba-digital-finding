<div
    x-data="{ open: false }"
    x-on:open-export-modal.window="open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center"
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"
    ></div>

    <!-- Modal Content -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-4 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-4 sm:scale-95"
        class="relative bg-white rounded-t-2xl sm:rounded-2xl w-full sm:max-w-md p-6 mx-0 sm:mx-4 border border-slate-200"
    >
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold text-slate-800">Export Laporan</h3>
            <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('findings.export') }}" method="GET" class="space-y-4">
            
            <!-- Export Type -->
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1.5">Pilih Format</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex cursor-pointer rounded-xl border border-slate-200 bg-white p-3 shadow-sm focus:outline-none hover:bg-slate-50 transition-colors">
                        <input type="radio" name="type" value="xlsx" class="peer sr-only" checked>
                        <span class="flex items-center justify-between w-full">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                <span class="text-sm font-medium text-slate-700">Excel</span>
                            </span>
                        </span>
                        <span class="pointer-events-none absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-blue-600"></span>
                    </label>

                    <label class="relative flex cursor-pointer rounded-xl border border-slate-200 bg-white p-3 shadow-sm focus:outline-none hover:bg-slate-50 transition-colors">
                        <input type="radio" name="type" value="pdf" class="peer sr-only">
                        <span class="flex items-center justify-between w-full">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                <span class="text-sm font-medium text-slate-700">PDF</span>
                            </span>
                        </span>
                        <span class="pointer-events-none absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-blue-600"></span>
                    </label>
                </div>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1.5">Rentang Waktu</label>
                <div class="flex items-center gap-2">
                    <input type="date" name="start_date" required class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-slate-400 text-sm">s/d</span>
                    <input type="date" name="end_date" required class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" @click="setTimeout(() => open = false, 500)" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path></svg>
                    Mulai Unduh
                </button>
            </div>
        </form>
    </div>
</div>
