<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('findings.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-xl font-bold text-slate-800">{{ __('Report a Finding') }}</h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-lg mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm" x-data="{ showPreview: false, previewUrl: null }">
                <!-- Helper Text -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path></svg>
                        <p class="text-sm text-blue-800 font-medium">Mohon isi data di bawah dengan jelas. Jika ada kolom yang tidak Anda ketahui, tanyakan pada supervisor area.</p>
                    </div>
                </div>

                <form
                    id="createFindingForm"
                    action="{{ route('findings.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >
                    @csrf

                    <!-- Area -->
                    <div>
                        <label class="block text-base font-bold text-slate-800 mb-2.5">Lokasi Area</label>
                        <select name="area_id" required class="w-full px-4 py-3.5 text-base bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">-- Ketuk untuk Pilih Area --</option>
                            @foreach(\App\Models\Area::all() as $area)
                                <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('area_id')" class="mt-1" />
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-base font-bold text-slate-800 mb-2.5">Detail Lokasi Spesifik</label>
                        <input type="text" name="location" required placeholder="Contoh: Mesin Cutting Nomor 3" value="{{ old('location') }}" class="w-full px-4 py-3.5 text-base bg-slate-50 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <x-input-error :messages="$errors->get('location')" class="mt-1" />
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-base font-bold text-slate-800 mb-2.5">Kategori Temuan</label>
                        <select name="category_id" required class="w-full px-4 py-3.5 text-base bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">-- Ketuk untuk Pilih Kategori --</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                    </div>

                    <!-- Risk Level -->
                    <div>
                        <label class="block text-base font-bold text-slate-800 mb-2.5">Tingkat Risiko (Bahaya)</label>
                        <select name="risk_level_id" required class="w-full px-4 py-3.5 text-base bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">-- Ketuk untuk Pilih Risiko --</option>
                            @foreach(\App\Models\RiskLevel::all() as $risk)
                                <option value="{{ $risk->id }}">{{ $risk->name }} (Harus selesai dalam {{ $risk->sla_hours }} jam)</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('risk_level_id')" class="mt-1" />
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-base font-bold text-slate-800 mb-2.5">Ceritakan Masalahnya (Deskripsi)</label>
                        <textarea name="description" rows="5" required placeholder="Tulis secara singkat apa yang Anda lihat dan mengapa itu berbahaya..." class="w-full px-4 py-3.5 text-base bg-slate-50 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>

                    <!-- Assign To -->
                    <div>
                        <label class="block text-base font-bold text-slate-800 mb-2.5">Tugaskan Ke Siapa? (PIC)</label>
                        <select name="assigned_to" class="w-full px-4 py-3.5 text-base bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">-- Boleh dikosongkan (Tentukan nanti) --</option>
                            @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Photo Upload with Preview -->
                    <div>
                        <label class="block text-base font-bold text-slate-800 mb-1">Ambil Foto Bukti</label>
                        <p class="text-sm text-slate-500 mb-3">Foto kondisi lapangan agar teknisi paham masalahnya.</p>
                        
                        <!-- Camera Button (Hidden input) -->
                        <div class="relative w-full">
                            <input
                                type="file"
                                name="photo"
                                accept="image/*"
                                capture="environment"
                                id="cameraInput"
                                class="hidden"
                                @change="
                                    if ($event.target.files.length) {
                                        showPreview = true;
                                        previewUrl = URL.createObjectURL($event.target.files[0]);
                                    }
                                "
                            >
                            <label for="cameraInput" class="cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-blue-300 rounded-2xl bg-blue-50 hover:bg-blue-100 hover:border-blue-400 transition-colors shadow-sm">
                                <svg class="w-8 h-8 text-blue-500 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                                </svg>
                                <span class="text-sm font-semibold text-blue-700">Buka Kamera / Ambil Foto</span>
                            </label>
                        </div>
                        
                        <!-- Image Preview -->
                        <div x-show="showPreview" x-cloak class="mt-4 relative">
                            <img :src="previewUrl" alt="Preview" class="w-full h-56 object-cover rounded-2xl border-4 border-slate-200 shadow-md">
                            <button type="button" @click="showPreview = false; previewUrl = null; document.getElementById('cameraInput').value = '';" class="absolute -top-3 -right-3 bg-rose-600 text-white p-2 rounded-full shadow-lg hover:bg-rose-700 transition-colors border-2 border-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 pb-8">
                        <button
                            type="button"
                            @click="$dispatch('open-confirm-submit-finding')"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <x-confirm-modal
        id="submit-finding"
        title="Submit Finding?"
        message="Temuan ini akan dicatat dan dikirimkan ke PIC yang ditunjuk. Pastikan data sudah benar."
        confirmText="Ya, Kirim"
        cancelText="Batal"
    />

    @push('scripts')
    <script>
        window.addEventListener('confirmed-submit-finding', () => {
            document.getElementById('createFindingForm').submit();
        });
    </script>
    @endpush
</x-app-layout>
