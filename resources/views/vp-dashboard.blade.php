<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('VP Dashboard - Executive Summary') }}
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="glass rounded-2xl p-6 shadow-xl shadow-slate-200/50">
                <h3 class="text-lg font-heading font-bold text-slate-800 mb-4">Department Performance (SLA Compliance)</h3>
                <div class="relative h-96 w-full">
                    <canvas id="vpChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('vpChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Production', 'Maintenance', 'Quality Control', 'Warehouse'],
                    datasets: [
                        {
                            label: 'On Time Closing (%)',
                            data: [95, 82, 100, 90],
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderRadius: 6
                        },
                        {
                            label: 'Overdue (%)',
                            data: [5, 18, 0, 10],
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, max: 100 }
                    }
                }
            });
        });
    </script>
</x-app-layout>
