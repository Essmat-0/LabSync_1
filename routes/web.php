<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EquipmentController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;


Route::get('/', [EquipmentController::class, 'index'])->name('equipment.index');



Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('dashboards.admin'))->middleware('role:1')->name('admin.dashboard');

    Route::get('/labmanager/dashboard', fn() => view('dashboards.labmanager'))->middleware('role:4')->name('labmanager.dashboard');

    Route::get('/pi/dashboard', fn() => view('dashboards.pi'))->middleware('role:2')->name('pi.dashboard');

    Route::get('/auditor/dashboard', fn() => view('dashboards.auditor'))->middleware('role:null')->name('auditor.dashboard');
});

Route::middleware(['auth', 'role:1'])->group(function () {
    Route::post('/adminAddUser', [AdminController::class, 'store'])->name('admin.users.store');
    Route::delete('/adminDeleteUser', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});