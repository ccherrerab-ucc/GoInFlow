<?php

use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\CaracteristicaController;
use App\Http\Controllers\AspectoController;
use App\Http\Controllers\EvidenciaController;
use App\Http\Controllers\ResultadoController;
use App\Http\Controllers\FlujoEjecucionController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/dashboards', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboards');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//rutas administrador
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/inicio',    [DashboardController::class, 'index'])->name('administrator.dashboard');
Route::get('/factor', function () { return view('administrator.factor');})->name('administrator.factor');
Route::get('/caracteristicas2', function () { return view('VistaFactores2.Index');})->name('VistaFactores2.Index');
Route::get('/aspectos_por_evaluar', function () { return view('administrator.aspectos_por_evaluar');})->name('administrator.aspectos_por_evaluar');
//Route::get('/evidencias', function () { return view('administrator.evidencias');})->name('administrator.evidencias');
Route::get('/resultados', function () { return view('administrator.resultados');})->name('administrator.resultados');
Route::get('/admin_users', function () { return view('administrator.admin_users');})->name('administrator.admin_users');
Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('administrator.auditoria');
Route::get('/ayuda', function () { return view('administrator.ayuda');})->name('administrator.ayuda');


Route::get('/departamentos/{area}', [DepartamentoController::class, 'getByArea']);

Route::resource('factores', FactorController::class);
Route::resource('caracteristicas', CaracteristicaController::class);
Route::resource('aspectos', AspectoController::class);
Route::resource('evidencias', EvidenciaController::class);
Route::resource('resultados', ResultadoController::class);

// Flujo de aprobación de evidencias
Route::prefix('flujo')->name('flujo.')->group(function () {
    Route::post('{evidencia}/iniciar',   [FlujoEjecucionController::class, 'iniciar'])->name('iniciar');
    Route::post('{evidencia}/decision',  [FlujoEjecucionController::class, 'decision'])->name('decision');
    Route::post('{evidencia}/reiniciar', [FlujoEjecucionController::class, 'reiniciar'])->name('reiniciar');
});


Route::resource('usuarios', UsuarioController::class);

require __DIR__.'/auth.php';
