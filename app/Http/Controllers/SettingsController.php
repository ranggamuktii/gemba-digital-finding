<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Area;
use App\Models\Category;
use App\Models\RiskLevel;
use App\Models\Department;
use Spatie\Permission\Models\Role;

class SettingsController extends Controller
{
    // Middleware is applied in routes/web.php

    public function index()
    {
        return redirect()->route('settings.users');
    }

    public function users()
    {
        $users = User::with(['roles', 'department'])->latest()->get();
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        return view('settings.users.index', compact('users', 'departments', 'roles'));
    }

    public function areas()
    {
        $areas = Area::orderBy('name')->get();
        return view('settings.areas.index', compact('areas'));
    }

    public function categories()
    {
        $categories = Category::orderBy('name')->get();
        return view('settings.categories.index', compact('categories'));
    }

    public function riskLevels()
    {
        $riskLevels = RiskLevel::orderBy('sla_hours')->get();
        return view('settings.risk-levels.index', compact('riskLevels'));
    }

    public function departments()
    {
        $departments = Department::orderBy('name')->get();
        return view('settings.departments.index', compact('departments'));
    }
}
