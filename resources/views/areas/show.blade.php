<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-heading font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Area QR Code: ') }} {{ $area->name }}
            </h2>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-medium shadow-lg shadow-indigo-200 transition-all">
                Print QR Code
            </button>
        </div>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8 space-y-6 text-center">
            <div class="glass rounded-3xl p-10 shadow-2xl shadow-indigo-200/50 bg-white print:shadow-none print:bg-transparent">
                <h3 class="text-3xl font-heading font-bold text-slate-800 mb-2">{{ $area->name }}</h3>
                <p class="text-slate-500 mb-8 font-medium">Scan here to report a finding in this area.</p>
                
                <div class="inline-block p-4 bg-white rounded-2xl shadow-inner border border-slate-100">
                    {!! $qrCode !!}
                </div>

                <div class="mt-8 text-sm text-slate-400 break-all">
                    {{ $url }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
