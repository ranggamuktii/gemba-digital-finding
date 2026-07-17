<x-guest-layout>
    <!-- Welcome Header for Context -->
    <div class="mb-8 text-center">
        <div
            class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Gemba Finding</h1>
        <p class="text-sm text-slate-500 mt-2">Silakan masuk menggunakan Username atau Email Anda.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-bold text-slate-700 mb-2">Username / Email</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                autocomplete="username" placeholder="Contoh: budi.santoso atau admin@example.com"
                class="block w-full px-4 py-3 text-sm bg-slate-50 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            <x-input-error :messages="$errors->get('username')" class="mt-2 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi (Password)</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="Masukkan kata sandi..."
                class="block w-full px-4 py-3 text-sm bg-slate-50 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm" />
        </div>

        <!-- Submit -->
        <div class="pt-2">
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                Masuk Sekarang
            </button>
        </div>

        <!-- Helpful note -->
        <p class="text-center text-sm text-slate-500 mt-6">
            Lupa kata sandi? Hubungi tim IT / HRD.
        </p>
    </form>
</x-guest-layout>