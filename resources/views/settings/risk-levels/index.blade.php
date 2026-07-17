@extends('settings.layout')

@section('settings_content')
<div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h3 class="text-lg font-semibold text-slate-800">Risk Levels</h3>
        <p class="text-sm text-slate-500 mt-1">Manage risk levels and their SLA (Service Level Agreement) in hours.</p>
    </div>
    <button type="button" @click="$dispatch('open-modal', 'add-risk-level')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shrink-0 whitespace-nowrap">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Add Risk Level
    </button>
</div>

<div class="p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[500px]">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-6 text-xs font-medium text-slate-500 uppercase tracking-wider">Risk Level Name</th>
                    <th class="py-3 px-6 text-xs font-medium text-slate-500 uppercase tracking-wider">SLA (Hours)</th>
                    <th class="py-3 px-6 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($riskLevels as $risk)
                <tr class="hover:bg-slate-50/50">
                    <td class="py-4 px-6 text-sm font-medium text-slate-800">
                        <span class="{{ $risk->name === 'High' ? 'text-rose-600' : ($risk->name === 'Medium' ? 'text-amber-600' : 'text-sky-600') }}">
                            {{ $risk->name }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-600">{{ $risk->sla_hours }} hours</td>
                    <td class="py-4 px-6 text-sm text-right">
                        <div class="flex items-center justify-end gap-3 sm:gap-4">
                            <button type="button" @click="$dispatch('open-modal', 'edit-risk-{{ $risk->id }}')" class="text-blue-600 hover:text-blue-800 font-medium whitespace-nowrap">Edit</button>
                            <form action="{{ route('risk-levels.destroy', $risk) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus risk level ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium whitespace-nowrap">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>

            <!-- Edit Modal -->
            <x-modal name="edit-risk-{{ $risk->id }}" focusable>
                <form method="POST" action="{{ route('risk-levels.update', $risk) }}" class="p-6">
                    @csrf
                    @method('PUT')
                    <h2 class="text-lg font-semibold text-slate-800 mb-4">Edit Risk Level</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Risk Level Name</label>
                            <input type="text" name="name" value="{{ $risk->name }}" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">SLA (Hours)</label>
                            <input type="number" name="sla_hours" value="{{ $risk->sla_hours }}" required min="1" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700">Save Changes</button>
                    </div>
                </form>
            </x-modal>
            @empty
            <tr>
                <td colspan="3" class="py-8 text-center text-slate-500">No risk levels found.</td>
            </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<x-modal name="add-risk-level" focusable>
    <form method="POST" action="{{ route('risk-levels.store') }}" class="p-6">
        @csrf
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Add Risk Level</h2>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Risk Level Name</label>
                <input type="text" name="name" required placeholder="e.g. Critical" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">SLA (Hours)</label>
                <input type="number" name="sla_hours" required min="1" placeholder="e.g. 24" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Cancel</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700">Add Risk Level</button>
        </div>
    </form>
</x-modal>
@endsection
