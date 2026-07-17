<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RiskLevelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['url'] ?? route('dashboard'));
    })->name('notifications.read');

    // Settings & Master Data (Super Admin Only)
    Route::middleware([\Spatie\Permission\Middleware\RoleMiddleware::class.':Super Admin'])->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SettingsController::class, 'index'])->name('index');
        Route::get('/users', [\App\Http\Controllers\SettingsController::class, 'users'])->name('users');
        Route::get('/areas', [\App\Http\Controllers\SettingsController::class, 'areas'])->name('areas');
        Route::get('/categories', [\App\Http\Controllers\SettingsController::class, 'categories'])->name('categories');
        Route::get('/risk-levels', [\App\Http\Controllers\SettingsController::class, 'riskLevels'])->name('risk-levels');
        Route::get('/departments', [\App\Http\Controllers\SettingsController::class, 'departments'])->name('departments');
    });

    // Master Data CRUD (for use in Settings)
    Route::middleware([\Spatie\Permission\Middleware\RoleMiddleware::class.':Super Admin'])->group(function () {
        Route::resource('departments', DepartmentController::class)->except(['index', 'show', 'create', 'edit']);
        Route::resource('areas', AreaController::class)->except(['index', 'create', 'edit']); // Keep show for QR Code
        Route::resource('categories', CategoryController::class)->except(['index', 'show', 'create', 'edit']);
        Route::resource('risk-levels', RiskLevelController::class)->except(['index', 'show', 'create', 'edit']);
        Route::post('users/{user}/role', [\App\Http\Controllers\UserController::class, 'updateRole'])->name('settings.users.updateRole');
        Route::post('users/{user}/department', [\App\Http\Controllers\UserController::class, 'updateDepartment'])->name('settings.users.updateDepartment');
    });

    // Finding Core Engine
    Route::get('findings/export', [\App\Http\Controllers\FindingController::class, 'export'])->name('findings.export');
    Route::resource('findings', \App\Http\Controllers\FindingController::class);
    Route::post('findings/{finding}/actions', [\App\Http\Controllers\FindingActionController::class, 'store'])->name('findings.actions.store');
});

require __DIR__.'/auth.php';
