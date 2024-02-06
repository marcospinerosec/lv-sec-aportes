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
    Route::post('/procesar', [App\Http\Controllers\HomeController::class, 'procesar']);

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
