<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- PWA Meta Tags -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#0d9488">
        <link rel="apple-touch-icon" href="{{ asset('icon-192.png') }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        
        <title>{{ config('app.name', 'Gemba Digital Finding') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
            [x-cloak] { display: none !important; }
        </style>

        @stack('styles')

        <!-- Global Notification Listener -->
        @auth
        <script type="module">
            document.addEventListener("DOMContentLoaded", function() {
                if (window.Echo) {
                    window.Echo.private('App.Models.User.' + {{ auth()->id() }})
                        .notification((notification) => {
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: { type: 'info', message: notification.message }
                            }));
                        });
                }
            });
        </script>
        @endauth
    </head>
    <body class="antialiased text-slate-700 bg-slate-50">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-slate-200">
                    <div class="max-w-5xl mx-auto py-4 px-4 sm:px-6">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 pb-20 sm:pb-6">
                {{ $slot }}
            </main>
        </div>

        <!-- Global Toast -->
        <x-toast />

        <!-- Global Export Modal -->
        <x-export-modal />

        <!-- Global Page Loader -->
        <x-page-loader />

        @stack('scripts')
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js');
                });
            }
        </script>
    </body>
</html>
