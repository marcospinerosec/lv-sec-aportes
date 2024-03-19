<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
});

Auth::routes([
    'register' => false, // Deshabilitar el registro
    'reset' => false,    // Deshabilitar la recuperación de contraseña
    'verify' => false,   // Deshabilitar la verificación de correo electrónico
]);



Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/ddjjs/ddjj', [App\Http\Controllers\DDJJController::class, 'index'])->name('ddjjs.ddjj');
    Route::get('/ddjjs/anteriores', [App\Http\Controllers\DDJJController::class, 'anteriores'])->name('ddjjs.anteriores');
    Route::post('/ddjjs/listar', [App\Http\Controllers\DDJJController::class, 'listar']);
    Route::post('/procesar', [App\Http\Controllers\DDJJController::class, 'procesar']);
    Route::post('/generar', [App\Http\Controllers\DDJJController::class, 'generar']);

     Route::get('/empleados/index', [App\Http\Controllers\EmpleadoController::class, 'index'])->name('empleados.index');
    //agregar account
    Route::get('/empleados/create', [App\Http\Controllers\EmpleadoController::class, 'create'])->name('empleados.nuevo');
    Route::put('/empleados/save', [App\Http\Controllers\EmpleadoController::class, 'save']);
    //editar /empleados
    //Route::get('/empleados/edit', [App\Http\Controllers\EmpleadoController::class, 'edit'])->name('empleados.editar');
    Route::get('/empleados/edit/{id}', [App\Http\Controllers\EmpleadoController::class, 'edit'])->name('empleados.editar');
    Route::put('/empleados/editar/{id}', [App\Http\Controllers\EmpleadoController::class, 'update']);
    //eliminar /empleados
    //Route::delete('/empleados/eliminar/{id}', [App\Http\Controllers\EmpleadoController::class, 'destroy']);
    Route::get('/empleados/eliminar/{empresa}', [App\Http\Controllers\EmpleadoController::class, 'eliminar'])->name('empleados.eliminar');
    //detalle
    Route::get('/empleados/detalle/{id}', [App\Http\Controllers\EmpleadoController::class, 'detalle'])->name('empleados.detalle');

    Route::get('/empleados/importar/{empresa}', [App\Http\Controllers\EmpleadoController::class, 'importar'])->name('empleados.importar');
    Route::put('/empleados/procesar', [App\Http\Controllers\EmpleadoController::class, 'procesar']);

    Route::get('/empleados/formatoArchivo', [App\Http\Controllers\EmpleadoController::class, 'formatoArchivo'])->name('empleados.formato');

});

Route::get('/empresa', function () {
    return view('empresa');
});

Route::get('/formulario', function () {
    return view('formulario');
});

Route::get('/pago', function () {
    return view('pago');
});

Route::get('/calendario', function () {
    return view('calendario');
});
