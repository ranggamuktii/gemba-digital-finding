<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        // Prevents Super Admin from removing their own super admin role accidentally
        if ($user->id === auth()->id() && $user->hasRole('Super Admin') && $validated['role'] !== 'Super Admin') {
            return back()->with('error', 'Anda tidak dapat menghapus akses Super Admin dari diri sendiri.');
        }

        $user->syncRoles([$validated['role']]);
        return back()->with('success', 'Role pengguna berhasil diperbarui.');
    }

    public function updateDepartment(Request $request, User $user)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $user->update(['department_id' => $validated['department_id']]);
        return back()->with('success', 'Departemen pengguna berhasil diperbarui.');
    }
}
