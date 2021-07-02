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

Route::group(['middleware'=>['guest']],function(){

    Route::get('/','Auth\LoginController@showLoginForm');
    Route::get('/login','Auth\LoginController@showLoginForm');
   Route::post('/login', 'Auth\LoginController@login')->name('login');
   Route::get('/logout','Auth\LoginController@showLoginForm');
});

Route::group(['middleware'=>['auth']],function(){
    
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    
    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' => ['Almacenero']], function () {
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/accesorio/actualizar', 'AccesorioController@update');
        Route::post('/accesorio/registrar', 'AccesorioController@store');
        Route::post('/accesorio/eliminar', 'AccesorioController@destroy');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::get('/articulo/selectAccesorio', 'ArticuloController@selectAccesorio');

        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');

    });

    Route::group(['middleware' => ['Vendedor']], function () {
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::post('/cliente/registrar2', 'ClienteController@store2');
        Route::put('/cliente/actualizar', 'ClienteController@update');        
        Route::put('/cliente/actualizar2', 'ClienteController@update2');        
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta'); 

        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar', 'ventaController@store');
        Route::put('/venta/desactivar', 'ventaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'ventaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'ventaController@obtenerDetalles');
    });

    Route::group(['middleware' => ['Administrador']], function () {
        
        Route::get('/accesorio', 'AccesorioController@index');
        Route::get('/getDataUser', 'PersonaController@getDataUser');
        Route::get('/getDataFac', 'FacturasController@getDataFac');

        Route::put('/persona/actualizar', 'UserController@update');
                
        Route::post('/soap', 'soap@soap');
        Route::get('/soap/getData/{dato}', 'soap@getData');
        
        Route::get('/libro', 'LibroController@index');
        Route::post('/libro/registrar', 'LibroController@store');
        Route::put('/libro/actualizar', 'LibroController@update');
        Route::post('/libro/eliminar','LibroController@destroy');

        Route::get('/sector', 'SectorController@index');
        Route::post('/sector/registrar', 'SectorController@store');
        Route::put('/sector/actualizar', 'SectorController@update');
        Route::put('/sector/desactivar', 'SectorController@desactivar');
        Route::put('/sector/activar', 'SectorController@activar');
        Route::get('/oficina/selectOficina', 'OficinaController@selectOficina');
        // Route::get('/sector/selectAccesorio', 'SectorController@selectAccesorio');

        Route::get('/barrio', 'BarrioController@index');
        Route::post('/barrio/registrar', 'BarrioController@store');
        Route::put('/barrio/actualizar', 'BarrioController@update');
        Route::put('/barrio/desactivar', 'BarrioController@desactivar');
        Route::put('/barrio/activar', 'BarrioController@activar');
        Route::get('/sector/selectSector', 'SectorController@selectSector');

        Route::get('/fdservicio', 'Fac_det_servicioController@index');
        Route::post('/fdservicio/registrar', 'Fac_det_servicioController@store');
        Route::post('/fdservicio/eliminar', 'Fac_det_servicioController@destroy');
        Route::put('/fdservicio/actualizar', 'Fac_det_servicioController@update');
        Route::put('/fdservicio/desactivar', 'Fac_det_servicioController@desactivar');
        Route::put('/fdservicio/activar', 'Fac_det_servicioController@activar');
        Route::get('/fac_servicio/selectFac_servicio', 'Fac_servicioController@selectFac_servicio');

        Route::get('/fac_servicio', 'Fac_servicioController@index');
        Route::post('/fac_servicio/registrar', 'Fac_servicioController@store');
        Route::post('/fac_servicio/eliminar', 'Fac_servicioController@destroy');
        Route::put('/fac_servicio/actualizar', 'Fac_servicioController@update');
        
    });

    // URL::forceScheme('https');
});

//Route::get('/home', 'HomeController@index')->name('home');
