<?php

use App\Http\Controllers\Api\ActividadApiController;
use App\Http\Controllers\Api\CatalogoApiController;
use App\Http\Controllers\Api\CirculacionApiController;
use App\Http\Controllers\Api\SocioApiController;
use App\Http\Controllers\Api\StaffAuthApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| API para Socio / Lector
|--------------------------------------------------------------------------
*/
Route::prefix('socio')->group(function () {
    // Endpoints públicos
    Route::post('/login', [SocioApiController::class, 'login']);
    Route::get('/catalogo', [SocioApiController::class, 'catalogo']);

    // Endpoints autenticados
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [SocioApiController::class, 'logout']);
        Route::get('/dashboard', [SocioApiController::class, 'dashboard']);
        Route::get('/perfil', [SocioApiController::class, 'dashboard']);
        Route::get('/multas', [SocioApiController::class, 'multas']);
        Route::post('/reservas', [SocioApiController::class, 'reservar']);
        Route::delete('/reservas/{reserva}', [SocioApiController::class, 'cancelarReserva']);

        // Favoritos
        Route::get('/favoritos', [SocioApiController::class, 'favoritos']);
        Route::post('/favoritos', [SocioApiController::class, 'agregarFavorito']);
        Route::delete('/favoritos/{isbn}', [SocioApiController::class, 'eliminarFavorito']);
    });
});

/*
|--------------------------------------------------------------------------
| API para Personal (Administrador / Bibliotecario)
|--------------------------------------------------------------------------
| Autenticación por token Bearer de Sanctum. Cada endpoint exige además el
| permiso spatie correspondiente, igual que el panel web.
*/
Route::post('/staff/login', [StaffAuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/staff/logout', [StaffAuthApiController::class, 'logout']);

    // Actividad del sistema
    Route::get('/actividad', [ActividadApiController::class, 'index'])->middleware('can:prestamos.ver');

    // Catálogo
    Route::get('/libros', [CatalogoApiController::class, 'libros'])->middleware('can:libros.ver');
    Route::get('/libros/{libro}', [CatalogoApiController::class, 'libro'])->middleware('can:libros.ver');
    Route::get('/categorias', [CatalogoApiController::class, 'categorias'])->middleware('can:categorias.ver');
    Route::get('/autores', [CatalogoApiController::class, 'autores'])->middleware('can:autores.ver');
    Route::get('/editoriales', [CatalogoApiController::class, 'editoriales'])->middleware('can:editoriales.ver');
    Route::get('/ejemplares', [CatalogoApiController::class, 'ejemplares'])->middleware('can:ejemplares.ver');
    Route::get('/socios', [CatalogoApiController::class, 'socios'])->middleware('can:socios.ver');

    // Circulación y préstamos
    Route::get('/prestamos', [CirculacionApiController::class, 'prestamosIndex'])->middleware('can:prestamos.ver');
    Route::post('/prestamos', [CirculacionApiController::class, 'prestar'])->middleware('can:prestamos.crear');
    Route::get('/prestamos/{prestamo}', [CirculacionApiController::class, 'prestamoShow'])->middleware('can:prestamos.ver');
    Route::get('/devoluciones', [CirculacionApiController::class, 'devolucionesIndex'])->middleware('can:devoluciones.ver');
    Route::post('/devoluciones', [CirculacionApiController::class, 'devolver'])->middleware('can:devoluciones.crear');
    Route::get('/devoluciones/{devolucion}', [CirculacionApiController::class, 'devolucionShow'])->middleware('can:devoluciones.ver');
    Route::get('/multas', [CirculacionApiController::class, 'multasIndex'])->middleware('can:multas.ver');
    Route::patch('/multas/{multa}/pagar', [CirculacionApiController::class, 'pagarMulta'])->middleware('can:multas.editar');
    Route::get('/reservas', [CirculacionApiController::class, 'reservasIndex'])->middleware('can:reservas.ver');
});
