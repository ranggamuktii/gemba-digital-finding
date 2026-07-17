@extends('settings.layout')

@section('settings_content')
<div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h3 class="text-lg font-semibold text-slate-800">Areas (Locations)</h3>
        <p class="text-sm text-slate-500 mt-1">Manage factory areas and generate QR codes for reporting.</p>
    </div>
    <button type="button" @click="$dispatch('open-modal', 'add-area')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shrink-0 whitespace-nowrap">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Add Area
    </button>
</div>

<div class="p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[500px]">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-6 text-xs font-medium text-slate-500 uppercase tracking-wider">Area Name</th>
                    <th class="py-3 px-6 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($areas as $area)
                <tr class="hover:bg-slate-50/50">
                    <td class="py-4 px-6 text-sm font-medium text-slate-800">{{ $area->name }}</td>
                    <td class="py-4 px-6 text-sm text-right">
                        <div class="flex items-center justify-end gap-3 sm:gap-4">
                            <a href="{{ route('areas.show', $area) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 font-medium whitespace-nowrap">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" /></svg>
                                <span>QR Code</span>
                            </a>
                            <button type="button" @click="$dispatch('open-modal', 'edit-area-{{ $area->id }}')" class="text-blue-600 hover:text-blue-800 font-medium whitespace-nowrap">Edit</button>
                            <form action="{{ route('areas.destroy', $area) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus area ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium whitespace-nowrap">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <x-modal name="edit-area-{{ $area->id }}" focusable>
                    <form method="POST" action="{{ route('areas.update', $area) }}" class="p-6">
                        @csrf
                        @method('PUT')
                        <h2 class="text-lg font-semibold text-slate-800 mb-4">Edit Area</h2>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Area Name</label>
                            <input type="text" name="name" value="{{ $area->name }}" required class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700">Save Changes</button>
                        </div>
                    </form>
                </x-modal>
                @empty
                <tr>
                    <td colspan="2" class="py-8 text-center text-slate-500">No areas found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<x-modal name="add-area" focusable>
    <form method="POST" action="{{ route('areas.store') }}" class="p-6">
        @csrf
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Add New Area</h2>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Area Name</label>
            <input type="text" name="name" required placeholder="e.g. Assembly Line 1" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Cancel</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700">Add Area</button>
        </div>
    </form>
</x-modal>
@endsection
