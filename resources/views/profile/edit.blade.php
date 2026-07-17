<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-800">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-xl mx-auto px-4 sm:px-6 space-y-6">

            <!-- Profile Info Card -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-slate-100 border border-slate-200 text-slate-400 rounded-2xl flex items-center justify-center overflow-hidden shrink-0">
                        <svg class="w-11 h-11 mt-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-slate-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="space-y-4 border-t border-slate-100 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-slate-500">Jabatan / Role</span>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700">
                            {{ Auth::user()->roles->first()?->name ?? 'User' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-slate-500">Departemen</span>
                        <span class="text-sm font-semibold text-slate-800">
                            {{ Auth::user()->department?->name ?? '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-slate-500">Status Akun</span>
                        <span class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif (LDAP Synced)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Super Admin Settings Card -->
            @role('Super Admin')
            <div class="bg-indigo-50 rounded-xl border border-indigo-200 p-6">
                <h3 class="text-sm font-semibold text-indigo-800 mb-2">Super Admin Menu</h3>
                <p class="text-xs text-indigo-600 mb-4">Akses khusus untuk mengelola Master Data dan Pengguna.</p>
                <a href="{{ route('settings.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Buka Settings Hub
                </a>
            </div>
            @endrole

            <!-- Language Preferences -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Preferensi Bahasa</h3>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('lang.switch', 'id') }}" class="w-full sm:flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl border {{ app()->getLocale() === 'id' ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }} transition-colors font-medium text-sm">
                        <span class="text-xs font-bold px-1.5 py-0.5 rounded {{ app()->getLocale() === 'id' ? 'bg-blue-200 text-blue-800' : 'bg-slate-100 text-slate-500' }}">ID</span>
                        Bahasa Indonesia
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" class="w-full sm:flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl border {{ app()->getLocale() === 'en' ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }} transition-colors font-medium text-sm">
                        <span class="text-xs font-bold px-1.5 py-0.5 rounded {{ app()->getLocale() === 'en' ? 'bg-blue-200 text-blue-800' : 'bg-slate-100 text-slate-500' }}">EN</span>
                        English
                    </a>
                </div>
            </div>

            <!-- Big Logout Button -->
            <div class="pt-4" x-data>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    <button
                        type="button"
                        @click="$dispatch('open-confirm-logout')"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 text-sm font-semibold rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Logout Sekarang
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Logout Confirm Modal -->
    <x-confirm-modal
        id="logout"
        title="Konfirmasi Logout"
        message="Apakah Anda yakin ingin keluar dari sistem Gemba Finding?"
        confirmText="Ya, Logout"
        cancelText="Batal"
        variant="danger"
    />

    @push('scripts')
    <script>
        window.addEventListener('confirmed-logout', () => {
            document.getElementById('logoutForm').submit();
        });
    </script>
    @endpush
</x-app-layout>
