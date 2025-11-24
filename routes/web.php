<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin' ? redirect()->route('admin.home') : redirect()->route('user.home');
    }
    return redirect()->route('login');
});

// admin route (admin only)
Route::middleware(['auth', 'is_admin'])->group(function () {
    // replaced inline view with controller so data is provided
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.home');

    // users
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // groups
    Route::get('/admin/groups', [GroupController::class, 'index'])->name('admin.groups');
    Route::post('/admin/groups', [GroupController::class, 'store'])->name('admin.groups.store');
    Route::put('/admin/groups/{group}', [GroupController::class, 'update'])->name('admin.groups.update');
    Route::delete('/admin/groups/{group}', [GroupController::class, 'destroy'])->name('admin.groups.destroy');

    // links resource (only index/store/update/destroy needed)
    Route::get('/admin/links', [LinkController::class, 'index'])->name('admin.links');
    Route::post('/admin/links', [LinkController::class, 'store'])->name('admin.links.store');
    Route::put('/admin/links/{link}', [LinkController::class, 'update'])->name('admin.links.update');
    Route::delete('/admin/links/{link}', [LinkController::class, 'destroy'])->name('admin.links.destroy');

    // reports
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');

    // add other admin routes below...
});

// user route (any authenticated user with role 'user' or admin can access — adjust if needed)
Route::middleware('auth')->group(function () {
    // user dashboard (authenticated users)
    Route::get('/user', [UserDashboardController::class, 'index'])->name('user.home');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// auth scaffolding routes
require __DIR__.'/auth.php';

// fallback: any unmatched route -> login (guest) or role home if authenticated
Route::fallback(function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin' ? redirect()->route('admin.home') : redirect()->route('user.home');
    }
    return redirect()->route('login');
});
