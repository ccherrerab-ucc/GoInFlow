<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\CaracteristicaController;
use App\Http\Controllers\AspectoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//rutas administrador
Route::get('/administrator.app', function () {
        return view('administrator.app');
    })->name('administrator.app');

Route::get('/inicio', function () { return view('administrator.dashboard');})->name('administrator.dashboard');
Route::get('/factor', function () { return view('administrator.factor');})->name('administrator.factor');
Route::get('/caracteristicas2', function () { return view('VistaFactores2.Index');})->name('VistaFactores2.Index');
Route::get('/aspectos_por_evaluar', function () { return view('administrator.aspectos_por_evaluar');})->name('administrator.aspectos_por_evaluar');
Route::get('/evidencia', function () { return view('administrator.evidencia');})->name('administrator.evidencia');
Route::get('/resultados', function () { return view('administrator.resultados');})->name('administrator.resultados');
Route::get('/admin_users', function () { return view('administrator.admin_users');})->name('administrator.admin_users');
Route::get('/auditoria', function () { return view('administrator.auditoria');})->name('administrator.auditoria');
Route::get('/ayuda', function () { return view('administrator.ayuda');})->name('administrator.ayuda');


Route::get('/departamentos/{area}', [DepartamentoController::class, 'getByArea']);

Route::resource('factores', FactorController::class);
Route::resource('caracteristicas', CaracteristicaController::class);
Route::resource('aspectos', AspectoController::class);

require __DIR__.'/auth.php';
