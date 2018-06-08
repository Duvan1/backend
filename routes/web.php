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
	Route::get('username/{username}', 'UserController@UserName');
	Route::resource('products', 'ProductoController');
	Route::resource('detalles', 'Detalles_ventaController');
	Route::resource('cliente', 'ClienteController');
	Route::resource('empleado', 'EmpleadoController');
	Route::resource('venta', 'VentaController');
	Route::get('mas-vendido', 'ProductoController@topTen');
	Route::get('mas-vendido-fechas/{f1}/{f2}/{orden}', 'ProductoController@topTenRangoFechas');
	Route::get('ganancias/{tipo}/{value}', 'VentaController@ganancias');
	Route::get('detalles_de_venta/{id_venta}', 'VentaController@detallesDeLaVenta');

	/*
//Route::post('login', 'UserController@login');
Route::post('products', 'ProductoController@store');
//Route::get('products/tuhermana', 'ProductoController@tuHermana');
Route::resource('venta', 'VentaController');
Route::resource('empleado', 'EmpleadoController');
Route::resource('cliente', 'ClienteController');*/
});



