@props([
    'title' => 'Tidak ada data',
    'message' => 'Belum ada data yang tersedia saat ini.',
    'actionUrl' => null,
    'actionText' => null,
])

<div class="flex flex-col items-center justify-center py-12 px-6">
    <!-- Empty Illustration SVG -->
    <svg class="w-24 h-24 text-slate-300 mb-6" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
    </svg>

    <h3 class="text-base font-semibold text-slate-600 mb-1">{{ $title }}</h3>
    <p class="text-sm text-slate-400 text-center max-w-xs">{{ $message }}</p>

    @if($actionUrl)
    <a href="{{ $actionUrl }}" class="mt-5 inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        {{ $actionText }}
    </a>
    @endif
</div>
