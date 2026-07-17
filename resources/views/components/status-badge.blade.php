@props(['status'])

@php
    $styles = [
        'OPEN' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'IN_PROGRESS' => 'bg-sky-50 text-sky-700 border border-sky-200',
        'WAITING_VERIFICATION' => 'bg-violet-50 text-violet-700 border border-violet-200',
        'CLOSED' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'OVERDUE' => 'bg-rose-50 text-rose-700 border border-rose-200',
    ];

    // Shorter labels for mobile friendliness
    $labels = [
        'OPEN' => 'Open',
        'IN_PROGRESS' => 'Progress',
        'WAITING_VERIFICATION' => 'Verifikasi',
        'CLOSED' => 'Closed',
        'OVERDUE' => 'Overdue',
    ];

    $dotColors = [
        'OPEN' => 'bg-amber-400',
        'IN_PROGRESS' => 'bg-sky-400',
        'WAITING_VERIFICATION' => 'bg-violet-400',
        'CLOSED' => 'bg-emerald-400',
        'OVERDUE' => 'bg-rose-400',
    ];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg whitespace-nowrap {{ $styles[$status] ?? 'bg-slate-50 text-slate-600 border border-slate-200' }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$status] ?? 'bg-slate-400' }}"></span>
    {{ $labels[$status] ?? $status }}
</span>
