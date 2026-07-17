<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gemba Finding') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="antialiased text-slate-700 bg-slate-50">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">
            <!-- Removed redundant logo block -->

            <!-- Login Card -->
            <div class="w-full max-w-sm bg-white rounded-2xl border border-slate-200 p-6">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="mt-6 text-xs text-slate-400">&copy; {{ date('Y') }} Gemba Finding. All rights reserved.</p>
        </div>
        
        <!-- Global Toast -->
        <x-toast />
        
        <!-- Global Page Loader -->
        <x-page-loader />
    </body>
</html>
