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
/** validar rutas , solo que se loge  */
Route::group(['middleware' => 'auth'], function() 
{
    Route::get('/administracion','HomeController@administracion');
       
});
Route::get('/', function () {
    return view('welcome');
});

Route::resource('administracion/roles', 'RolController');
Route::resource('administracion/usuario', 'UserController'); /** el alias de con que se llamara y con el controlador que trabajara */
/** el resourse llama a todos los metodos (index, create, update, delete, show) */
Route::get('administracion/roles_ajax', 'RolController@roles_ajax'); 
Route::get('administracion/usuarios_ajax', 'UserController@ajax');  /** llamar ajax del metodo */
Route::post('administracion/buscar_cedula_usuario', ['as'=>'buscar_cedula_usuario','uses'=>'UserController@buscar_cedula_usuario']);
Route::get('administracion/cargar_roles', 'UserController@cargar_roles')->name('cargar_roles');      
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('administracion/buscar_email_usuario', 'UserController@buscar_email_usuario')->name('buscar_email_usuario');
