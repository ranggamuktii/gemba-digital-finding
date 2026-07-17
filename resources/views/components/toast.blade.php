<div
    x-data="{
        toasts: [],
        addToast(type, message) {
            const id = Date.now();
            this.toasts.push({ id, type, message, progress: 100 });
            const interval = setInterval(() => {
                const toast = this.toasts.find(t => t.id === id);
                if (toast) {
                    toast.progress -= 2;
                    if (toast.progress <= 0) {
                        clearInterval(interval);
                        this.removeToast(id);
                    }
                } else {
                    clearInterval(interval);
                }
            }, 80);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
        colors: {
            success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
            error: 'bg-rose-50 border-rose-200 text-rose-800',
            warning: 'bg-amber-50 border-amber-200 text-amber-800',
            info: 'bg-sky-50 border-sky-200 text-sky-800',
        },
        progressColors: {
            success: 'bg-emerald-400',
            error: 'bg-rose-400',
            warning: 'bg-amber-400',
            info: 'bg-sky-400',
        },
        icons: {
            success: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            error: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            warning: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008z',
            info: 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
        }
    }"
    x-init="
        @if(session('success')) addToast('success', '{{ session('success') }}'); @endif
        @if(session('error')) addToast('error', '{{ session('error') }}'); @endif
        @if(session('warning')) addToast('warning', '{{ session('warning') }}'); @endif
        @if(session('info')) addToast('info', '{{ session('info') }}'); @endif
        window.addEventListener('toast', (e) => addToast(e.detail.type, e.detail.message));
    "
    class="fixed top-6 left-1/2 -translate-x-1/2 z-[70] flex flex-col items-center gap-3 w-full max-w-sm px-4"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            :class="colors[toast.type]"
            class="w-full border rounded-xl px-4 py-3 flex items-start gap-3 relative overflow-hidden"
        >
            <!-- Icon -->
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" :d="icons[toast.type]"></path>
            </svg>
            <!-- Message -->
            <p class="text-sm font-medium flex-1" x-text="toast.message"></p>
            <!-- Close -->
            <button @click="removeToast(toast.id)" class="flex-shrink-0 opacity-60 hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <!-- Progress bar -->
            <div class="absolute bottom-0 left-0 h-0.5 transition-all duration-100" :class="progressColors[toast.type]" :style="'width:' + toast.progress + '%'"></div>
        </div>
    </template>
</div>
