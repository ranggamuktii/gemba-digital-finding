<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold text-slate-800">
                {{ __('Findings') }}
            </h2>
            <!-- Desktop actions -->
            <div class="hidden sm:flex items-center gap-2">
                <button type="button" @click="$dispatch('open-export-modal')" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export
                </button>
                <a href="{{ route('findings.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    New Finding
                </a>
            </div>
        </div>
    </x-slot>

    <!-- DataTables CSS (desktop only) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    @push('styles')
    <style>
        /* DataTable overrides for clean look */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: #0d9488;
            box-shadow: 0 0 0 2px rgba(13,148,136,0.15);
        }
        table.dataTable { border-collapse: collapse !important; }
        table.dataTable tbody tr { background-color: transparent; }
        table.dataTable.no-footer { border-bottom: 1px solid #e2e8f0; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #0d9488 !important; color: white !important; border: none !important; border-radius: 0.5rem; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f1f5f9 !important; border: none !important; border-radius: 0.5rem; }
        .dataTables_wrapper .dataTables_info { font-size: 0.75rem; color: #94a3b8; }
    </style>
    @endpush

    <div class="py-4 sm:py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">

            @php
                $findings = \App\Models\Finding::with(['category', 'area', 'riskLevel'])->latest()->get();
            @endphp

            @if($findings->isEmpty())
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <x-empty-state
                        title="Belum ada temuan"
                        message="Mulai laporkan temuan pertama Anda dari lantai produksi."
                        :actionUrl="route('findings.create')"
                        actionText="Buat Temuan Baru"
                    />
                </div>
            @else

            <!-- Removed Redundant Mobile Export Button -->

            <!-- Mobile Card List -->
            <div class="sm:hidden space-y-3">
                @foreach($findings as $finding)
                <a href="{{ route('findings.show', $finding) }}" class="block bg-white rounded-xl border border-slate-200 p-4 active:bg-slate-50 transition-colors">
                    <div class="flex items-start justify-between mb-2">
                        <p class="text-sm font-semibold text-slate-700">{{ $finding->finding_no }}</p>
                        <x-status-badge :status="$finding->status" />
                    </div>
                    <p class="text-[15px] text-slate-600 mb-3 line-clamp-2">{{ Str::limit($finding->description, 80) }}</p>
                    <div class="space-y-1.5 mt-3">
                        <div class="flex items-center gap-2 text-[13px] text-slate-500">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span class="truncate">{{ $finding->area?->name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-[13px] text-slate-500">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                            <span class="truncate">{{ $finding->category?->name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-[13px] text-slate-500">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <span class="truncate">{{ $finding->riskLevel?->name ?? '-' }}</span>
                        </div>
                    </div>
                    <!-- Explicit Button for Boomers -->
                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <div class="flex items-center justify-center gap-2 w-full py-2.5 bg-blue-50 text-blue-700 font-bold text-sm rounded-lg group-hover:bg-blue-100 transition-colors">
                            Lihat Detail Temuan
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="hidden sm:block bg-white rounded-xl border border-slate-200 p-5">
                <div class="overflow-x-auto">
                    <table id="findingsTable" class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-medium text-slate-400 uppercase tracking-wider">
                                <th class="py-3 px-4">Finding No</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Area</th>
                                <th class="py-3 px-4">Risk</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($findings as $finding)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-4 text-sm font-medium text-slate-700">{{ $finding->finding_no }}</td>
                                <td class="py-3 px-4 text-sm text-slate-500">{{ $finding->category?->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-sm text-slate-500">{{ $finding->area?->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-sm text-slate-500">{{ $finding->riskLevel?->name ?? '-' }}</td>
                                <td class="py-3 px-4"><x-status-badge :status="$finding->status" /></td>
                                <td class="py-3 px-4 text-sm text-slate-400">{{ $finding->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('findings.show', $finding) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View →</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @endif
        </div>
    </div>



    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            if (window.innerWidth >= 640 && $('#findingsTable tbody tr').length > 0) {
                $('#findingsTable').DataTable({
                    pageLength: 15,
                    order: [],
                    language: {
                        search: "",
                        searchPlaceholder: "Search findings...",
                        lengthMenu: "Show _MENU_",
                        info: "Showing _START_-_END_ of _TOTAL_",
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
