<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ParkingSpaceController;
use App\Http\Controllers\VehicleEntryController;
use App\Http\Controllers\TipoVehiculoWebController;

Route::post('/notificaciones/leidas', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notificaciones.leer');

// Redirección al panel principal
Route::get('/', function () {
    return redirect('/dashboard');
});

// Dashboard principal
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Panel administrativo
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware('auth');
Route::get('/home', [AdminController::class, 'index'])->name('admin.index')->middleware('auth');

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Gestión de roles
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('roles', RoleController::class);
});

// Gestión de usuarios
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UserController::class);
});

// Gestión de documentos
Route::middleware(['auth'])->group(function () {
    Route::get('/documentos', [DocumentoController::class, 'index'])->name('documentos.index');
    Route::get('/documentos/crear', [DocumentoController::class, 'create'])->name('documentos.create');
    Route::post('/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/{id}/editar', [DocumentoController::class, 'edit'])->name('documentos.edit');
    Route::put('/documentos/{id}', [DocumentoController::class, 'update'])->name('documentos.update');
    Route::delete('/documentos/{id}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');
    Route::get('/documentos/descargar/{id}', [DocumentoController::class, 'descargar'])->name('documentos.descargar');

    Route::get('/documentos/{id}', [DocumentoController::class, 'show'])->name('documentos.show');
});

// routes/web.php

Route::middleware('auth')->group(function () {
    Route::resource('categorias', CategoriaController::class)->only(['index', 'create', 'store']);
});

Route::resource('categorias', CategoriaController::class)->middleware('auth');

Route::resource('tipo_documentos', TipoDocumentoController::class);

// Logs y exportaciones
Route::middleware('auth')->group(function () {
    Route::get('/logs/exportar/excel', [DocumentoController::class, 'exportarExcel'])->name('logs.exportar.excel');
    Route::get('/logs/exportar/pdf', [DocumentoController::class, 'exportarPDF'])->name('logs.exportar.pdf');
    Route::get('/log', [DocumentoController::class, 'exportarLog'])->name('documentos.log.index');
});
// Agrupar todo bajo middleware de autenticación
Route::middleware(['auth'])->group(function () {
    // Perfil de usuario


    // Ruta principal (panel de parqueadero)
    Route::get('/', [VehicleEntryController::class, 'index'])->name('parking.dashboard');

    // Rutas para vehículos
    Route::resource('vehicles', VehicleController::class);

    // Rutas para espacios de parqueo
    Route::resource('parking-spaces', ParkingSpaceController::class);

    // Rutas para entradas de vehículos
    Route::prefix('parking')->name('parking.')->group(function () {
        Route::get('/', [VehicleEntryController::class, 'index'])->name('dashboard');
        Route::get('/history', [VehicleEntryController::class, 'history'])->name('history');
        Route::post('/entrada', [VehicleEntryController::class, 'registerEntry'])->name('entry');
        Route::post('/salida', [VehicleEntryController::class, 'registerExit'])->name('exit');
    });




Route::middleware(['auth'])->group(function () {
    Route::resource('tipo_vehiculos', TipoVehiculoWebController::class);
});

});
require __DIR__ . '/auth.php';
