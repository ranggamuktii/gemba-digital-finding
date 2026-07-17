<div x-data="{ mobileMenu: false }">
    <!-- Desktop Top Nav -->
    <nav class="hidden sm:block bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-14">
                <!-- Left: Logo + Links -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="hidden sm:block text-sm font-semibold text-slate-800">Gemba Finding</span>
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-700 bg-blue-50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('findings.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('findings.*') ? 'text-blue-700 bg-blue-50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                            {{ __('Findings') }}
                        </a>
                    </div>
                </div>

                <!-- Right: User Menu -->
                <div class="hidden sm:flex items-center gap-3">
                    <!-- Language Switcher -->
                    <div class="flex items-center bg-slate-100 rounded-lg p-0.5">
                        <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 text-xs font-medium rounded-md transition-colors {{ app()->getLocale() === 'id' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">ID</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs font-medium rounded-md transition-colors {{ app()->getLocale() === 'en' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">EN</a>
                    </div>

                    <!-- Notifications Dropdown -->
                    <x-dropdown align="right" width="80">
                        <x-slot name="trigger">
                            <button class="relative flex items-center p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border border-white"></span>
                                </span>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <h3 class="text-sm font-semibold text-slate-800">Notifikasi</h3>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-0 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/50' }}">
                                        <p class="text-sm text-slate-800 font-medium mb-0.5">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                        <p class="text-xs text-slate-500">{{ $notification->created_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-center">
                                        <p class="text-sm text-slate-500">Tidak ada notifikasi baru.</p>
                                    </div>
                                @endforelse
                            </div>
                        </x-slot>
                    </x-dropdown>

                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-slate-600 hover:text-slate-800 rounded-lg hover:bg-slate-50 transition-colors">
                                <div class="w-7 h-7 bg-slate-100 border border-slate-200 text-slate-400 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                                    <svg class="w-5 h-5 mt-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            @role('Super Admin')
                            <x-dropdown-link :href="route('settings.index')">
                                {{ __('Settings') }}
                            </x-dropdown-link>
                            @endrole
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Bottom Tab Bar -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 sm:hidden z-50">
        <div class="grid grid-cols-5 h-16 px-1 relative">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-end gap-1 h-full pb-2 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                <span class="text-[11px] font-medium">Dashboard</span>
            </a>

            <!-- Findings -->
            <a href="{{ route('findings.index') }}" class="flex flex-col items-center justify-end gap-1 h-full pb-2 {{ request()->routeIs('findings.index') || request()->routeIs('findings.show') ? 'text-blue-600' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span class="text-[11px] font-medium">Findings</span>
            </a>

            <!-- Create (FAB Style) -->
            <a href="{{ route('findings.create') }}" class="relative flex flex-col items-center justify-end h-full pb-2">
                <div class="absolute -top-5 w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center shadow-lg shadow-blue-300 border-4 border-white">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-blue-700">Report</span>
            </a>

            <!-- Export -->
            <button type="button" @click="$dispatch('open-export-modal')" class="flex flex-col items-center justify-end gap-1 h-full pb-2 text-slate-400 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span class="text-[11px] font-medium">Export</span>
            </button>

            <!-- Profile -->
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-end gap-1 h-full pb-2 {{ request()->routeIs('profile.*') ? 'text-blue-600' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span class="text-[11px] font-medium">Profile</span>
            </a>
        </div>
    </div>
</div>
