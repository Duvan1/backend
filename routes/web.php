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

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix'=> 'api', 'middleware' => 'cors'], function (){
	Route::post('login', 'UserController@login');
	Route::post('register', 'UserController@register');
	Route::resource('products', 'ProductoController');
	Route::resource('detalles', 'Detalles_ventaController');
	Route::resource('cliente', 'ClienteController');
	Route::resource('venta', 'VentaController');
	Route::get('mas-vendido', 'ProductoController@topTen');
	/*
//Route::post('login', 'UserController@login');
Route::post('products', 'ProductoController@store');
//Route::get('products/tuhermana', 'ProductoController@tuHermana');
Route::resource('venta', 'VentaController');
Route::resource('empleado', 'EmpleadoController');
Route::resource('cliente', 'ClienteController');*/
});



