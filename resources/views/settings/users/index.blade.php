@extends('settings.layout')

@section('settings_content')
<div class="p-6 border-b border-slate-200 flex justify-between items-center">
    <div>
        <h3 class="text-lg font-semibold text-slate-800">User Management</h3>
        <p class="text-sm text-slate-500 mt-1">Manage user roles and department assignments.</p>
    </div>
</div>

<div class="p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[700px]">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-6 text-xs font-medium text-slate-500 uppercase tracking-wider">Name / Email</th>
                    <th class="py-3 px-6 text-xs font-medium text-slate-500 uppercase tracking-wider">Department</th>
                    <th class="py-3 px-6 text-xs font-medium text-slate-500 uppercase tracking-wider">Role</th>
                    <th class="py-3 px-6 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/50">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-400 flex items-center justify-center shrink-0 overflow-hidden">
                                <svg class="w-7 h-7 mt-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-600">
                        {{ $user->department?->name ?? 'None' }}
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-600">
                        @if($user->roles->count() > 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $user->hasRole('Super Admin') ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ $user->roles->first()->name }}
                            </span>
                        @else
                            <span class="text-slate-400">No Role</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-right">
                        <button type="button" @click="$dispatch('open-modal', 'edit-user-{{ $user->id }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors whitespace-nowrap">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.12l-2.83.928.928-2.83a4.5 4.5 0 011.12-1.89l12.72-12.72zM16.862 4.487L19.5 7.125" /></svg>
                            Edit Access
                        </button>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <x-modal name="edit-user-{{ $user->id }}" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-slate-800 mb-4">Edit Access: {{ $user->name }}</h2>
                        
                        <!-- Role Form -->
                        <form method="POST" action="{{ route('settings.users.updateRole', $user) }}" class="mb-6 pb-6 border-b border-slate-100">
                            @csrf
                            <label class="block text-sm font-medium text-slate-700 mb-1">User Role</label>
                            <div class="flex gap-2">
                                <select name="role" required class="flex-1 px-4 py-2 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Select Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700">Update Role</button>
                            </div>
                        </form>

                        <!-- Department Form -->
                        <form method="POST" action="{{ route('settings.users.updateDepartment', $user) }}">
                            @csrf
                            <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                            <div class="flex gap-2">
                                <select name="department_id" required class="flex-1 px-4 py-2 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Select Department --</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700">Update Dept</button>
                            </div>
                        </form>

                        <div class="mt-6 flex justify-end">
                            <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Close</button>
                        </div>
                    </div>
                </x-modal>
                @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-slate-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
