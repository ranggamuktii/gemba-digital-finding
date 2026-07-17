<div 
    x-data="{ loading: false }"
    x-init="
        window.addEventListener('beforeunload', () => { loading = true; });
        window.addEventListener('pageshow', (e) => { if (e.persisted) loading = false; });
        document.addEventListener('submit', (e) => {
            if (!e.defaultPrevented && e.target.target !== '_blank') {
                loading = true;
            }
        });
    "
    x-show="loading"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[99999] bg-slate-900/80 backdrop-blur-md flex flex-col items-center justify-center"
    style="display: none;"
>
    <!-- Premium Loader Container -->
    <div class="flex flex-col items-center justify-center">
        <div class="relative flex items-center justify-center w-24 h-24">
            <!-- Pulsing glow behind -->
            <div class="absolute inset-0 bg-blue-500 rounded-full blur-2xl opacity-40 animate-pulse"></div>
            
            <!-- Outer dashed ring (slow spin reverse) -->
            <svg class="absolute w-24 h-24 text-blue-300/30 animate-[spin_4s_linear_infinite_reverse]" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-dasharray="10 6"></circle>
            </svg>

            <!-- Middle ring (fast spin) -->
            <svg class="absolute w-20 h-20 text-blue-500 animate-spin" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="46" fill="none" stroke="currentColor" stroke-width="3" stroke-dasharray="120" stroke-linecap="round"></circle>
            </svg>
            
            <!-- Center Logo -->
            <div class="absolute w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/50 ring-2 ring-white/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>

        <!-- Typography -->
        <div class="mt-8 flex flex-col items-center">
            <span class="text-sm font-bold text-white tracking-[0.1em] uppercase">Gemba Finding</span>
            <span class="text-xs text-blue-300 font-medium mt-1.5 animate-pulse tracking-wide">Mohon tunggu sebentar...</span>
        </div>
    </div>
</div>
