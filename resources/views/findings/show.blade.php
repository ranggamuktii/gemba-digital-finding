<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('findings.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-lg font-semibold text-slate-800">{{ $finding->finding_no }}</h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                <!-- Main Detail -->
                <div class="lg:col-span-2 space-y-5">

                    <!-- Status & Description Card -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1 min-w-0 mr-3">
                                <p class="text-base font-bold text-slate-800 mb-1">{{ Str::limit($finding->description, 100) }}</p>
                                <p class="text-[15px] text-slate-500">{{ $finding->area?->name ?? '-' }} · {{ $finding->location }}</p>
                            </div>
                            <x-status-badge :status="$finding->status" />
                        </div>

                        <!-- Info Grid - 1 Col on mobile, 2 col on desktop for readability -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-5 border-t border-slate-100 pt-5">
                            <div class="bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-1">Kategori</p>
                                <p class="text-[15px] font-semibold text-slate-800">{{ $finding->category?->name ?? '-' }}</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-1">Risk Level</p>
                                <p class="text-[15px] font-semibold {{ $finding->riskLevel?->name === 'High' ? 'text-rose-600' : ($finding->riskLevel?->name === 'Medium' ? 'text-amber-600' : 'text-sky-600') }}">
                                    {{ $finding->riskLevel?->name ?? '-' }} ({{ $finding->riskLevel?->sla_hours ?? '-' }}h SLA)
                                </p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-1">PIC (Penanggung Jawab)</p>
                                <p class="text-[15px] font-semibold text-slate-800">{{ $finding->assignee?->name ?? 'Belum ditugaskan' }}</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-1">Batas Waktu Perbaikan</p>
                                <p class="text-[15px] font-semibold text-slate-800">{{ $finding->due_date?->format('d M Y, H:i') ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Photo -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">Foto Temuan</h3>
                        @if($finding->photo)
                        <img src="{{ Storage::url($finding->photo) }}" alt="Finding Photo" class="w-full h-56 sm:h-72 object-cover rounded-lg border border-slate-100">
                        @else
                        <div class="w-full h-40 bg-slate-50 rounded-lg flex flex-col items-center justify-center border border-dashed border-slate-200">
                            <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v13.5A1.5 1.5 0 003.75 21z" />
                            </svg>
                            <p class="text-xs text-slate-400">Tidak ada foto</p>
                        </div>
                        @endif
                    </div>

                    <!-- Corrective Action Photos -->
                    @if($finding->actions->count() > 0)
                    <div class="bg-white rounded-xl border border-slate-200 p-5">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">Foto Perbaikan</h3>
                        <div class="space-y-3">
                            @foreach($finding->actions as $action)
                            <div class="bg-slate-50 rounded-lg p-3">
                                <p class="text-sm text-slate-700 mb-2">{{ $action->action_description }}</p>
                                @if($action->photo)
                                <img src="{{ Storage::url($action->photo) }}" alt="Action Photo" class="w-full h-40 object-cover rounded-lg border border-slate-100">
                                @endif
                                <p class="text-xs text-slate-400 mt-2">{{ $action->action_date?->format('d M Y, H:i') ?? $action->created_at->format('d M Y, H:i') }} · {{ $action->performer?->name ?? 'Unknown' }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-5">

                    <!-- PIC Action Form -->
                    @if($finding->status !== 'CLOSED')
                    <div class="bg-white rounded-xl border border-slate-200 p-5">
                        <h3 class="text-sm font-semibold text-slate-700 mb-4">Update Progress</h3>
                        <form
                            id="submitActionForm"
                            method="POST"
                            action="{{ route('findings.actions.store', $finding) }}"
                            enctype="multipart/form-data"
                            class="space-y-4"
                        >
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Tindakan yang Dilakukan</label>
                                <textarea name="action_description" rows="3" required placeholder="Jelaskan perbaikan yang dilakukan..." class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1.5">Foto Bukti Perbaikan (Kamera)</label>
                                <div class="relative w-full" x-data="{ showPreviewAction: false, previewUrlAction: null }">
                                    <input
                                        type="file"
                                        name="photo"
                                        accept="image/*"
                                        capture="environment"
                                        id="cameraInputAction"
                                        class="hidden"
                                        @change="
                                            if ($event.target.files.length) {
                                                showPreviewAction = true;
                                                previewUrlAction = URL.createObjectURL($event.target.files[0]);
                                            }
                                        "
                                    >
                                    <label for="cameraInputAction" class="cursor-pointer flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 hover:bg-slate-100 hover:border-blue-400 transition-colors">
                                        <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                                        </svg>
                                        <span class="text-xs font-medium text-slate-600">Ambil Foto / Upload</span>
                                    </label>
                                    
                                    <!-- Image Preview -->
                                    <div x-show="showPreviewAction" x-cloak class="mt-3 relative">
                                        <img :src="previewUrlAction" alt="Preview" class="w-full h-32 object-cover rounded-xl border border-slate-200 shadow-sm">
                                        <button type="button" @click="showPreviewAction = false; previewUrlAction = null; document.getElementById('cameraInputAction').value = '';" class="absolute top-2 right-2 bg-white/90 text-rose-600 p-1.5 rounded-lg shadow-sm hover:bg-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button
                                type="button"
                                @click="$dispatch('open-confirm-submit-action')"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                                Submit Action
                            </button>
                        </form>
                    </div>

                    <!-- Verify / Close Button (Manager Only) -->
                    @if($finding->status === 'WAITING_VERIFICATION' && (auth()->id() === $finding->created_by || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Manager')))
                    <div class="bg-white rounded-xl border border-slate-200 p-5">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">Verifikasi Temuan</h3>
                        <p class="text-xs text-slate-500 mb-4">Setelah dicek secara visual, verifikasi bahwa temuan ini sudah diperbaiki.</p>
                        <form id="closeFindingForm" method="POST" action="{{ route('findings.update', $finding) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="CLOSED">
                            <button
                                type="button"
                                @click="$dispatch('open-confirm-close-finding')"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Verifikasi & Tutup
                            </button>
                        </form>
                    </div>
                    @endif

                    @else
                    <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-5 text-center">
                        <svg class="w-8 h-8 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-semibold text-emerald-700 mb-1">Temuan Sudah Ditutup</p>
                        <p class="text-xs text-emerald-500">Tidak ada aksi lanjutan yang diperlukan.</p>
                    </div>
                    @endif

                    <!-- History Timeline -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5">
                        <h3 class="text-sm font-semibold text-slate-700 mb-4">Riwayat</h3>

                        @php
                            $histories = $finding->histories()->with('changedBy')->latest()->get();
                        @endphp

                        @if($histories->isEmpty())
                            <x-empty-state title="Belum ada riwayat" message="Riwayat perubahan akan muncul di sini." />
                        @else
                        <div class="relative ml-2">
                            <div class="absolute left-1 top-2 bottom-2 w-px bg-slate-200"></div>
                            <div class="space-y-4">
                                @foreach($histories as $history)
                                <div class="relative pl-6">
                                    <div class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full border-2 border-white
                                        {{ $history->new_status === 'CLOSED' ? 'bg-emerald-400' : ($history->new_status === 'OVERDUE' ? 'bg-rose-400' : ($history->new_status === 'WAITING_VERIFICATION' ? 'bg-violet-400' : 'bg-blue-400')) }}
                                    "></div>
                                    <p class="text-sm font-medium text-slate-700">
                                        @if($history->old_status)
                                            {{ $history->old_status }} → {{ $history->new_status }}
                                        @else
                                            Finding created
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $history->created_at->format('d M Y, H:i') }}
                                        · {{ $history->changedBy?->name ?? 'System' }}
                                    </p>
                                    @if($history->remark)
                                    <p class="text-xs text-slate-400 italic">{{ $history->remark }}</p>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Modals -->
    <x-confirm-modal
        id="submit-action"
        title="Submit Perbaikan?"
        message="Tindakan perbaikan akan dicatat dan status akan berubah menjadi Waiting Verification."
        confirmText="Ya, Submit"
        cancelText="Batal"
    />

    <x-confirm-modal
        id="close-finding"
        title="Tutup Temuan?"
        message="Temuan akan ditandai sebagai selesai (Closed) dan tidak bisa diubah lagi."
        confirmText="Ya, Tutup"
        cancelText="Batal"
        variant="warning"
    />

    @push('scripts')
    <script>
        window.addEventListener('confirmed-submit-action', () => {
            document.getElementById('submitActionForm').submit();
        });
        window.addEventListener('confirmed-close-finding', () => {
            document.getElementById('closeFindingForm').submit();
        });
    </script>
    @endpush
</x-app-layout>
