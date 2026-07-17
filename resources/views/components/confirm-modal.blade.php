@props([
    'id' => 'confirm-modal',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin ingin melanjutkan?',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Batal',
    'variant' => 'primary',
])

@php
    $confirmColors = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white',
        'danger' => 'bg-rose-500 hover:bg-rose-600 focus:ring-rose-400 text-white',
        'warning' => 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-400 text-white',
    ];
@endphp

<div
    x-data="{ open: false, isSubmitting: false }"
    x-on:open-confirm-{{ $id }}.window="open = true; isSubmitting = false;"
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
        @click="if(!isSubmitting) open = false"
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
        <!-- Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-12 h-12 rounded-full {{ $variant === 'danger' ? 'bg-rose-50' : ($variant === 'warning' ? 'bg-amber-50' : 'bg-blue-50') }} flex items-center justify-center">
                @if($variant === 'danger')
                <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008z" />
                </svg>
                @else
                <svg class="w-6 h-6 {{ $variant === 'warning' ? 'text-amber-500' : 'text-blue-600' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                @endif
            </div>
        </div>

        <h3 class="text-lg font-semibold text-slate-800 text-center mb-1">{{ $title }}</h3>
        <p class="text-sm text-slate-500 text-center mb-6">{{ $message }}</p>

        <div class="flex flex-col sm:flex-row gap-3">
            <button
                @click="open = false"
                :disabled="isSubmitting"
                type="button"
                class="flex-1 px-4 py-3 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ $cancelText }}
            </button>
            <button
                @click="isSubmitting = true; $nextTick(() => $dispatch('confirmed-{{ $id }}'))"
                :disabled="isSubmitting"
                type="button"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-75 disabled:cursor-not-allowed {{ $confirmColors[$variant] ?? $confirmColors['primary'] }}"
            >
                <svg x-show="isSubmitting" x-cloak class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-show="!isSubmitting">{{ $confirmText }}</span>
                <span x-show="isSubmitting" x-cloak>Memproses...</span>
            </button>
        </div>
    </div>
</div>
