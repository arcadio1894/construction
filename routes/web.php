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
    return view('welcome');
});

//LANDING
Route::get('/nosotros', 'LandingController@about')->name('landing.about');
Route::get('/fabricacion', 'LandingController@manufacturing')->name('landing.manufacturing');
Route::get('/servicios', 'LandingController@service')->name('landing.service');
Route::get('/contacto', 'LandingController@contact')->name('landing.contact');


Auth::routes();

Route::middleware('auth')->group(function (){
    Route::prefix('dashboard')->group(function (){
        Route::get('/principal', 'HomeController@dashboard')->name('dashboard.principal');

        // TODO: Rutas módulo Accesos

        //USER
        Route::get('usuarios', 'UserController@index')->name('user.index')
            ->middleware('permission:list_user');
        Route::post('user/store', 'UserController@store')->name('user.store')
            ->middleware('permission:create_user');
        Route::post('user/update', 'UserController@update')->name('user.update')
            ->middleware('permission:update_user');
        Route::get('user/roles/{id}', 'UserController@getRoles')->name('user.roles')
            ->middleware('permission:update_user');
        Route::post('user/destroy', 'UserController@destroy')->name('user.destroy')
            ->middleware('permission:destroy_user');
        Route::get('/all/users', 'UserController@getUsers');
        Route::get('/user/roles/{id}', 'UserController@getRoles')->name('user.roles')
            ->middleware('permission:update_user');

        //CUSTOMER
        Route::get('/all/customers', 'CustomerController@getCustomers');
        Route::get('clientes', 'CustomerController@index')->name('customer.index');
        Route::get('crear/cliente', 'CustomerController@create')->name('customer.create');
        Route::post('customer/store', 'CustomerController@store')->name('customer.store');
        Route::get('/editar/cliente/{id}', 'CustomerController@edit')->name('customer.edit');
        Route::post('customer/update', 'CustomerController@update')->name('customer.update');
        Route::post('customer/destroy', 'CustomerController@destroy')->name('customer.destroy');
        Route::get('clientes/restore', 'CustomerController@restore')->name('customer.restore');
        Route::get('/all/customers/destroy', 'CustomerController@getCustomersDestroy');

        //MATERIAL TYPE
        Route::get('/all/materialtypes', 'MaterialTypeController@getMaterialTypes');
        Route::get('TiposMateriales', 'MaterialTypeController@index')->name('materialtype.index');
        Route::get('crear/tipomaterial', 'MaterialTypeController@create')->name('materialtype.create');
        Route::post('materialtype/store', 'MaterialTypeController@store')->name('materialtype.store');
        Route::get('/editar/tipomaterial/{id}', 'MaterialTypeController@edit')->name('materialtype.edit');
        Route::post('materialtype/update', 'MaterialTypeController@update')->name('materialtype.update');
        Route::post('materialtype/destroy', 'MaterialTypeController@destroy')->name('materialtype.destroy');

        //CATEGORY
        Route::get('/all/categories', 'CategoryController@getCategories');
        Route::get('Categorias', 'CategoryController@index')->name('category.index');
        Route::get('crear/categoria', 'CategoryController@create')->name('category.create');
        Route::post('category/store', 'CategoryController@store')->name('category.store');
        Route::get('/editar/categoria/{id}', 'CategoryController@edit')->name('category.edit');
        Route::post('category/update', 'CategoryController@update')->name('category.update');
        Route::post('category/destroy', 'CategoryController@destroy')->name('category.destroy');

        //ROL
        Route::get('roles', 'RoleController@index')->name('role.index')
            ->middleware('permission:list_role');
        Route::post('role/store', 'RoleController@store')->name('role.store')
            ->middleware('permission:create_role');
        Route::post('role/update', 'RoleController@update')->name('role.update')
            ->middleware('permission:update_role');
        Route::get('role/permissions/{id}', 'RoleController@getPermissions')->name('role.permissions')
            ->middleware('permission:update_role');
        Route::post('role/destroy', 'RoleController@destroy')->name('role.destroy')
            ->middleware('permission:destroy_role');
        Route::get('/all/roles', 'RoleController@getRoles');
        Route::get('role/permissions/{id}', 'RoleController@getPermissions')->name('role.permissions')
            ->middleware('permission:update_role');
        Route::get('/crear/rol', 'RoleController@create')->name('role.create');
        Route::get('/editar/rol/{id}', 'RoleController@edit');

        //PERMISSION
        Route::get('permisos', 'PermissionController@index')->name('permission.index')
            ->middleware('permission:list_permission');
        Route::post('permission/store', 'PermissionController@store')->name('permission.store')
            ->middleware('permission:create_permission');
        Route::post('permission/update', 'PermissionController@update')->name('permission.update')
            ->middleware('permission:update_permission');
        Route::post('permission/destroy', 'PermissionController@destroy')->name('permission.destroy')
            ->middleware('permission:destroy_permission');
        Route::get('/all/permissions', 'PermissionController@getPermissions');

        // MATERIAL
        Route::get('materiales', 'MaterialController@index')->name('material.index')
            ->middleware('permission:list_material');
        Route::get('crear/material', 'MaterialController@create')->name('material.create')
            ->middleware('permission:create_material');
        Route::post('material/store', 'MaterialController@store')->name('material.store')
            ->middleware('permission:create_material');
        Route::get('editar/material/{id}', 'MaterialController@edit')->name('material.edit')
            ->middleware('permission:update_material');
        Route::post('material/update', 'MaterialController@update')->name('material.update')
            ->middleware('permission:update_material');
        Route::post('material/destroy', 'MaterialController@destroy')->name('material.destroy')
            ->middleware('permission:destroy_material');
        Route::get('/all/materials', 'MaterialController@getAllMaterials')->name('all.materials')
            ->middleware('permission:list_material');

        //AREAS
        Route::get('areas', 'AreaController@index')->name('area.index')
            ->middleware('permission:list_area');
        Route::post('area/store', 'AreaController@store')->name('area.store')
            ->middleware('permission:create_area');
        Route::post('area/update', 'AreaController@update')->name('area.update')
            ->middleware('permission:update_area');
        Route::post('area/destroy', 'AreaController@destroy')->name('area.destroy')
            ->middleware('permission:destroy_area');
        Route::get('/all/areas', 'AreaController@getAreas');

        //WAREHOUSE
        Route::get('ver/almacenes/{area}', 'WarehouseController@index')->name('warehouse.index')
            ->middleware('permission:list_warehouse');
        Route::post('warehouse/store', 'WarehouseController@store')->name('warehouse.store')
            ->middleware('permission:create_warehouse');
        Route::post('warehouse/update', 'WarehouseController@update')->name('warehouse.update')
            ->middleware('permission:update_warehouse');
        Route::post('warehouse/destroy', 'WarehouseController@destroy')->name('warehouse.destroy')
            ->middleware('permission:destroy_warehouse');
        Route::get('/all/warehouses/{id_area}', 'WarehouseController@getWarehouses');

        //SHELF
        Route::get('ver/anaqueles/almacen/{almacen}/area/{area}', 'ShelfController@index')->name('shelf.index')
            ->middleware('permission:list_shelf');
        Route::post('shelf/store', 'ShelfController@store')->name('shelf.store')
            ->middleware('permission:create_shelf');
        Route::post('shelf/update', 'ShelfController@update')->name('shelf.update')
            ->middleware('permission:update_shelf');
        Route::post('shelf/destroy', 'ShelfController@destroy')->name('shelf.destroy')
            ->middleware('permission:destroy_shelf');
        Route::get('/all/shelves/{id_warehouse}', 'ShelfController@getShelves');

        //LEVEL
        Route::get('ver/niveles/anaquel/{anaquel}/almacen/{almacen}/area/{area}', 'LevelController@index')->name('level.index')
            ->middleware('permission:list_level');
        Route::post('level/store', 'LevelController@store')->name('level.store')
            ->middleware('permission:create_level');
        Route::post('level/update', 'LevelController@update')->name('level.update')
            ->middleware('permission:update_level');
        Route::post('level/destroy', 'LevelController@destroy')->name('level.destroy')
            ->middleware('permission:destroy_level');
        Route::get('/all/levels/{id_shelf}', 'LevelController@getLevels');

        //CONTAINER
        Route::get('ver/contenedores/nivel/{niveles}/anaquel/{anaqueles}/almacen/{almacen}/area/{area}', 'ContainerController@index')->name('container.index')
            ->middleware('permission:list_container');
        Route::post('container/store', 'ContainerController@store')->name('container.store')
            ->middleware('permission:create_container');
        Route::post('container/update', 'ContainerController@update')->name('container.update')
            ->middleware('permission:update_container');
        Route::post('container/destroy', 'ContainerController@destroy')->name('container.destroy')
            ->middleware('permission:destroy_container');
        Route::get('/all/containers/{id_level}', 'ContainerController@getContainers');

        //LOCATION
        Route::get('ubicaciones', 'LocationController@index')->name('location.index')
            ->middleware('permission:list_location');
        Route::get('/all/locations', 'LocationController@getLocations');

        // ENTRY
        Route::get('entradas/retaceria', 'EntryController@indexEntryScraps')->name('entry.scrap.index')
            ->middleware('permission:list_material');
        Route::get('entradas/compra', 'EntryController@indexEntryPurchase')->name('entry.purchase.index')
            ->middleware('permission:list_material');
        Route::get('crear/entrada/compra', 'EntryController@createEntryPurchase')->name('entry.purchase.create')
            ->middleware('permission:create_material');
        Route::get('crear/entrada/retacería', 'EntryController@createEntryScrap')->name('entry.scrap.create')
            ->middleware('permission:create_material');
        Route::post('entry_purchase/store', 'EntryController@storeEntryPurchase')->name('entry.purchase.store')
            ->middleware('permission:create_material');
        Route::post('entry_scrap/store', 'EntryController@storeEntryScrap')->name('entry.scrap.store')
            ->middleware('permission:create_material');
        Route::get('/get/materials', 'MaterialController@getJsonMaterials')
            ->middleware('permission:list_material');
        Route::get('/get/locations', 'LocationController@getJsonLocations')
            ->middleware('permission:list_material');
        Route::get('/get/items/{id_material}', 'ItemController@getJsonItems')
            ->middleware('permission:list_material');
        Route::get('/get/json/entries/purchase', 'EntryController@getJsonEntriesPurchase')
            ->middleware('permission:list_material');
        Route::get('/get/entries/purchase', 'EntryController@getEntriesPurchase')
            ->middleware('permission:list_material');
        Route::get('/get/json/entries/scrap', 'EntryController@getJsonEntriesScrap')
            ->middleware('permission:list_material');
        Route::get('/get/json/items/{detail_id}', 'ItemController@getJsonItemsDetail')
            ->middleware('permission:list_material');

        // OUTPUT
        Route::get('solicitudes/salida', 'OutputController@indexOutputRequest')->name('output.request.index')
            ->middleware('permission:list_material');
        Route::get('salidas', 'OutputController@indexOutputs')->name('output.confirm')
            ->middleware('permission:list_material');
        Route::get('crear/solicitud', 'OutputController@createOutputRequest')->name('output.request.create')
            ->middleware('permission:list_material');
        Route::post('ouput/store', 'OutputController@storeOutput')->name('output.request.store')
            ->middleware('permission:list_material');
        Route::get('/get/users', 'UserController@getUsers2');
        Route::get('/get/items/output/{id_material}', 'ItemController@getJsonItemsOutput')
            ->middleware('permission:list_material');
        Route::post('output_request/store', 'OutputController@storeOutputRequest')->name('output.request.store')
            ->middleware('permission:create_material');
        Route::get('/get/json/output/request', 'OutputController@getOutputRequest')
            ->middleware('permission:list_material');
        Route::get('/get/json/items/output/{output_id}', 'OutputController@getJsonItemsOutputRequest')
            ->middleware('permission:list_material');
        Route::post('output_request/attend', 'OutputController@attendOutputRequest')->name('output.attend')
            ->middleware('permission:create_material');
        Route::post('output_request/confirm', 'OutputController@confirmOutputRequest')->name('output.confirmed')
            ->middleware('permission:create_material');

    });
});

Route::get('/home', 'HomeController@index')->name('home');
