<?php

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

/* Route::get('/', function () {
    return view('welcome');
}); */

/* Route::get('/', "ClientesController@index"); */

/**
 * Sirve para no estar solicitando uno a uno los recursos como GET, POST, PUT y DELETE
 * como la linea anterior
 */
Route::resource('/', "ClientesController");
Route::resource('/registro', "ClientesController");