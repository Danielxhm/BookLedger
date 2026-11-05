<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\Admin\LibroController as LibroControllerAdmin;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\EjemplarController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\MultaController;
use App\Http\Controllers\BibliotecarioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrestamoController;

/*
|--------------------------------------------------------------------------
| Asistente de Instalación (Un solo uso / Auto-bloqueo)
|--------------------------------------------------------------------------
*/
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', [InstallController::class, 'index'])->name('index');
    Route::get('/database', [InstallController::class, 'database'])->name('database');
    Route::post('/process', [InstallController::class, 'process'])->name('process');
    Route::get('/success', [InstallController::class, 'success'])->name('success');
});

/*
|--------------------------------------------------------------------------
| Público
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});
/*
|--------------------------------------------------------------------------
| Panel de bibliotecarios / administradores
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Categorías
    |----------------------------------------------------------------------
    */
    Route::controller(CategoriaController::class)->prefix('categorias')->name('categorias.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:categorias.ver');
        Route::get('/create', 'create')->name('create')->middleware('can:categorias.crear');
        Route::post('/', 'store')->name('store')->middleware('can:categorias.crear');
        Route::get('/{categoria}/edit', 'edit')->name('edit')->middleware('can:categorias.editar');
        Route::put('/{categoria}', 'update')->name('update')->middleware('can:categorias.editar');
        Route::delete('/{categoria}', 'destroy')->name('destroy')->middleware('can:categorias.eliminar');
        Route::get('/{categoria}', 'show')->name('show')->middleware('can:categorias.ver');
    });

    /*
    |----------------------------------------------------------------------
    | Editoriales
    |----------------------------------------------------------------------
    */
    Route::controller(EditorialController::class)->prefix('editoriales')->name('editoriales.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:editoriales.ver');
        Route::get('/create', 'create')->name('create')->middleware('can:editoriales.crear');
        Route::post('/', 'store')->name('store')->middleware('can:editoriales.crear');
        Route::get('/{editorial}/edit', 'edit')->name('edit')->middleware('can:editoriales.editar');
        Route::put('/{editorial}', 'update')->name('update')->middleware('can:editoriales.editar');
        Route::delete('/{editorial}', 'destroy')->name('destroy')->middleware('can:editoriales.eliminar');
        Route::get('/{editorial}', 'show')->name('show')->middleware('can:editoriales.ver');
    });

    /*
    |----------------------------------------------------------------------
    | Autores
    |----------------------------------------------------------------------
    */
    Route::controller(AutorController::class)->prefix('autores')->name('autores.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:autores.ver');
        Route::get('/create', 'create')->name('create')->middleware('can:autores.crear');
        Route::post('/', 'store')->name('store')->middleware('can:autores.crear');
        Route::get('/{autor}/edit', 'edit')->name('edit')->middleware('can:autores.editar');
        Route::put('/{autor}', 'update')->name('update')->middleware('can:autores.editar');
        Route::delete('/{autor}', 'destroy')->name('destroy')->middleware('can:autores.eliminar');
        Route::get('/{autor}', 'show')->name('show')->middleware('can:autores.ver');
    });

    /*
    |----------------------------------------------------------------------
    | Ejemplares
    |----------------------------------------------------------------------
    */
    Route::controller(EjemplarController::class)->prefix('ejemplares')->name('ejemplares.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:ejemplares.ver');
        Route::get('/create', 'create')->name('create')->middleware('can:ejemplares.crear');
        Route::post('/', 'store')->name('store')->middleware('can:ejemplares.crear');
        Route::get('/{ejemplar}/edit', 'edit')->name('edit')->middleware('can:ejemplares.editar');
        Route::put('/{ejemplar}', 'update')->name('update')->middleware('can:ejemplares.editar');
        Route::delete('/{ejemplar}', 'destroy')->name('destroy')->middleware('can:ejemplares.eliminar');
        Route::get('/{ejemplar}', 'show')->name('show')->middleware('can:ejemplares.ver');
    });

    /*
    |----------------------------------------------------------------------
    | Devoluciones
    |----------------------------------------------------------------------
    */
    Route::controller(DevolucionController::class)->prefix('devoluciones')->name('devoluciones.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:devoluciones.ver');
        Route::get('/create', 'create')->name('create')->middleware('can:devoluciones.crear');
        Route::post('/', 'store')->name('store')->middleware('can:devoluciones.crear');
        Route::get('/{devolucion}', 'show')->name('show')->middleware('can:devoluciones.ver');
    });

    /*
    |----------------------------------------------------------------------
    | Libros
    |----------------------------------------------------------------------
    */
    Route::controller(LibroControllerAdmin::class)->prefix('libros')->name('libros.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:libros.ver');
        Route::get('/create', 'create')->name('create')->middleware('can:libros.crear');
        Route::post('/', 'store')->name('store')->middleware('can:libros.crear');
        Route::get('/{libro}/edit', 'edit')->name('edit')->middleware('can:libros.editar');
        Route::put('/{libro}', 'update')->name('update')->middleware('can:libros.editar');
        Route::delete('/{libro}', 'destroy')->name('destroy')->middleware('can:libros.eliminar');
        Route::get('/{libro}', 'show')->name('show')->middleware('can:libros.ver');
    });

    /*
    |----------------------------------------------------------------------
    | Socios
    |----------------------------------------------------------------------
    */
    Route::controller(SocioController::class)->prefix('socios')->name('socios.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:socios.ver');
        Route::get('/create', 'create')->name('create')->middleware('can:socios.crear');
        Route::post('/', 'store')->name('store')->middleware('can:socios.crear');
        Route::get('/{socio}/edit', 'edit')->name('edit')->middleware('can:socios.editar');
        Route::put('/{socio}', 'update')->name('update')->middleware('can:socios.editar');
        Route::delete('/{socio}', 'destroy')->name('destroy')->middleware('can:socios.eliminar');
        Route::get('/{socio}', 'show')->name('show')->middleware('can:socios.ver');
    });

    /*
    |----------------------------------------------------------------------
    | Multas
    |----------------------------------------------------------------------
    */
    Route::controller(MultaController::class)->prefix('multas')->name('multas.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:multas.ver');
        Route::get('/create', 'create')->name('create')->middleware('can:multas.crear');
        Route::post('/', 'store')->name('store')->middleware('can:multas.crear');
        Route::get('/{multa}/edit', 'edit')->name('edit')->middleware('can:multas.editar');
        Route::put('/{multa}', 'update')->name('update')->middleware('can:multas.editar');
        Route::delete('/{multa}', 'destroy')->name('destroy')->middleware('can:multas.eliminar');
        Route::patch('/{multa}/pagar', 'pagar')->name('pagar')->middleware('can:multas.editar');
    });
    // Notificaciones
    Route::post('/notificaciones/marcar-leidas', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notificaciones.marcarLeidas');
});


/*
|--------------------------------------------------------------------------
| bibliotecario
|--------------------------------------------------------------------------
*/
Route::controller(BibliotecarioController::class)->prefix('bibliotecarios')->name('bibliotecarios.')->group(function () {
    Route::get('/', 'index')->name('index')->middleware('can:bibliotecarios.ver');
    Route::get('/create', 'create')->name('create')->middleware('can:bibliotecarios.crear');
    Route::post('/', 'store')->name('store')->middleware('can:bibliotecarios.crear');
    Route::get('/{bibliotecario}/edit', 'edit')->name('edit')->middleware('can:bibliotecarios.editar');
    Route::put('/{bibliotecario}', 'update')->name('update')->middleware('can:bibliotecarios.editar');
    Route::delete('/{bibliotecario}', 'destroy')->name('destroy')->middleware('can:bibliotecarios.eliminar');
    Route::get('/{bibliotecario}', 'show')->name('show')->middleware('can:bibliotecarios.ver');
});

/*
|--------------------------------------------------------------------------
| Préstamos
|--------------------------------------------------------------------------
*/

Route::controller(PrestamoController::class)->prefix('prestamos')->name('prestamos.')->group(function () {
    Route::get('/', 'index')->name('index')->middleware('can:prestamos.ver');
    Route::get('/create', 'create')->name('create')->middleware('can:prestamos.crear');
    Route::post('/', 'store')->name('store')->middleware('can:prestamos.crear');
    Route::patch('/reservas/{reserva}/entregar', 'entregarReserva')->name('reservas.entregar')->middleware('can:prestamos.crear');
    Route::patch('/reservas/{reserva}/cancelar', 'cancelarReserva')->name('reservas.cancelar')->middleware('can:prestamos.editar');
    Route::get('/{prestamo}', 'show')->name('show')->middleware('can:prestamos.ver');
});
