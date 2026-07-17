<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-800">
            {{ __('messages.dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5 sm:py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 space-y-6">

            <!-- Greeting & Quick Action -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">{{ __('messages.welcome') }}</p>
                    <p class="text-xl font-bold text-slate-800">{{ Auth::user()->name }}</p>
                </div>
                <!-- Desktop Only: Big Report Button -->
                <div class="hidden sm:block">
                    <a href="{{ route('findings.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Lapor Temuan Baru
                    </a>
                </div>
            </div>

            <!-- KPI Cards — stacked vertically on mobile for readability -->
            @php
                $totalFindings = \App\Models\Finding::count();
                $openFindings = \App\Models\Finding::where('status', 'OPEN')->count();
                $overdueFindings = \App\Models\Finding::where('status', 'OVERDUE')->count();
                $closedFindings = \App\Models\Finding::where('status', 'CLOSED')->count();
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <!-- Total -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalFindings }}</p>
                    <p class="text-sm text-slate-400 mt-0.5">{{ __('messages.total_findings') }}</p>
                </div>

                <!-- Open -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-amber-600">{{ $openFindings }}</p>
                    <p class="text-sm text-slate-400 mt-0.5">{{ __('messages.open') }}</p>
                </div>

                <!-- Overdue -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-rose-500">{{ $overdueFindings }}</p>
                    <p class="text-sm text-slate-400 mt-0.5">{{ __('messages.overdue') }}</p>
                </div>

                <!-- Closed -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-emerald-600">{{ $closedFindings }}</p>
                    <p class="text-sm text-slate-400 mt-0.5">{{ __('messages.closed') }}</p>
                </div>
            </div>

            <!-- Charts & Lists -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                <!-- Pareto Chart -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5">
                    <h3 class="text-base font-semibold text-slate-700 mb-4">{{ __('messages.findings_by_category') }}</h3>
                    <div class="relative h-72 w-full">
                        <canvas id="paretoChart"></canvas>
                    </div>
                </div>

                <!-- Recent Findings -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-semibold text-slate-700">{{ __('messages.recent_findings') }}</h3>
                        <a href="{{ route('findings.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            {{ __('messages.view_all') }} →
                        </a>
                    </div>

                    @php
                        $recentFindings = \App\Models\Finding::with(['category', 'riskLevel'])->latest()->take(5)->get();
                    @endphp

                    @if($recentFindings->isEmpty())
                        <x-empty-state
                            title="Belum ada temuan"
                            message="Temuan akan muncul di sini setelah dilaporkan."
                            :actionUrl="route('findings.create')"
                            actionText="Buat Temuan Baru"
                        />
                    @else
                    <div class="space-y-3">
                        @foreach($recentFindings as $finding)
                        <a href="{{ route('findings.show', $finding) }}" class="block bg-slate-50 rounded-xl border border-slate-100 p-4 hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                            <div class="flex items-start justify-between mb-2">
                                <p class="text-base font-bold text-slate-700 group-hover:text-blue-800 transition-colors">{{ $finding->finding_no }}</p>
                                <x-status-badge :status="$finding->status" />
                            </div>
                            <p class="text-base text-slate-600 mb-3 truncate">{{ Str::limit($finding->description, 60) }}</p>
                            <div class="flex items-center justify-between border-t border-slate-200/60 pt-3">
                                <span class="text-sm text-slate-500">{{ $finding->created_at->diffForHumans() }}</span>
                                <div class="flex items-center gap-1.5 text-sm font-bold text-blue-600 group-hover:text-blue-700">
                                    Lihat Detail 
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('paretoChart');
            if (!ctx) return;

            @php
                $categories = \App\Models\Category::withCount('findings')->orderByDesc('findings_count')->get();
                $catLabels = $categories->pluck('name')->toArray();
                $catCounts = $categories->pluck('findings_count')->toArray();
                $total = array_sum($catCounts);
                $cumulative = [];
                $runningSum = 0;
                foreach ($catCounts as $count) {
                    $runningSum += $count;
                    $cumulative[] = $total > 0 ? round(($runningSum / $total) * 100) : 0;
                }
            @endphp

            new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($catLabels),
                    datasets: [{
                        type: 'bar',
                        label: 'Findings',
                        data: @json($catCounts),
                        backgroundColor: 'rgba(37, 99, 235, 0.7)', // Tailwind blue-600
                        borderRadius: 4,
                        order: 2
                    }, {
                        type: 'line',
                        label: 'Cumulative %',
                        data: @json($cumulative),
                        borderColor: 'rgba(244, 63, 94, 0.8)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(244, 63, 94, 1)',
                        xAxisID: 'x1',
                        order: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // This makes it a horizontal chart
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" } } },
                        x1: { type: 'linear', position: 'top', min: 0, max: 100, grid: { display: false }, ticks: { font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" }, callback: v => v + '%' } },
                        y: { grid: { display: false }, ticks: { font: { size: 11, family: "'Plus Jakarta Sans', sans-serif", color: '#475569' } } }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });

        // Listen for real-time Finding creations
        if (window.Echo) {
            window.Echo.channel('findings')
                .listen('FindingCreated', (e) => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { type: 'info', message: 'New Finding: ' + e.finding.finding_no }
                    }));
                });
        }
    </script>
    @endpush
</x-app-layout>
