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
Route::post('/emailcontact', 'EmailController@sendEmailContact')->name('email.contact');



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
        Route::get('clientes/restore', 'CustomerController@indexrestore')->name('customer.indexrestore');
        Route::get('/all/customers/destroy', 'CustomerController@getCustomersDestroy');
        Route::post('customer/restore', 'CustomerController@restore')->name('customer.restore');

        //SUPPLIER
        Route::get('/all/suppliers', 'SupplierController@getSuppliers')
            ->middleware('permission:list_supplier');
        Route::get('proveedores', 'SupplierController@index')->name('supplier.index')
            ->middleware('permission:list_supplier');
        Route::get('crear/proveedor', 'SupplierController@create')->name('supplier.create')
            ->middleware('permission:create_supplier');
        Route::post('supplier/store', 'SupplierController@store')->name('supplier.store')
            ->middleware('permission:create_supplier');
        Route::get('/editar/proveedor/{id}', 'SupplierController@edit')->name('supplier.edit')
            ->middleware('permission:update_supplier');
        Route::post('supplier/update', 'SupplierController@update')->name('supplier.update')
            ->middleware('permission:update_supplier');
        Route::post('supplier/destroy', 'SupplierController@destroy')->name('supplier.destroy')
            ->middleware('permission:destroy_supplier');
        Route::get('proveedores/restore', 'SupplierController@indexrestore')->name('supplier.indexrestore')
            ->middleware('permission:destroy_supplier');
        Route::get('/all/suppliers/destroy', 'SupplierController@getSuppliersDestroy')
            ->middleware('permission:destroy_supplier');
        Route::post('supplier/restore', 'SupplierController@restore')->name('supplier.restore')
            ->middleware('permission:destroy_supplier');

        //CONTACT NAME
        Route::get('/all/contacts', 'ContactNameController@getContacts');
        Route::get('contactos', 'ContactNameController@index')->name('contactName.index');
        Route::get('crear/contacto', 'ContactNameController@create')->name('contactName.create');
        Route::post('contact/store', 'ContactNameController@store')->name('contactName.store');
        Route::get('/editar/contacto/{id}', 'ContactNameController@edit')->name('contactName.edit');
        Route::post('contact/update', 'ContactNameController@update')->name('contactName.update');
        Route::post('contact/destroy', 'ContactNameController@destroy')->name('contactName.destroy');
        Route::get('contactos/restore', 'ContactNameController@indexrestore')->name('contactName.indexrestore');
        Route::get('/all/contacts/destroy', 'ContactNameController@getContactsDestroy');
        Route::post('contact/restore', 'ContactNameController@restore')->name('contactName.restore');

        //MATERIAL TYPE
        Route::get('/all/materialtypes', 'MaterialTypeController@getMaterialTypes');
        Route::get('TiposMateriales', 'MaterialTypeController@index')->name('materialtype.index');
        Route::get('crear/tipomaterial', 'MaterialTypeController@create')->name('materialtype.create');
        Route::post('materialtype/store', 'MaterialTypeController@store')->name('materialtype.store');
        Route::get('/editar/tipomaterial/{id}', 'MaterialTypeController@edit')->name('materialtype.edit');
        Route::post('materialtype/update', 'MaterialTypeController@update')->name('materialtype.update');
        Route::post('materialtype/destroy', 'MaterialTypeController@destroy')->name('materialtype.destroy');
        Route::get('/get/types/{subcategory_id}', 'MaterialTypeController@getTypesBySubCategory');

        //SUB TYPE
        Route::get('/all/subtypes', 'SubTypeController@getSubTypes');
        Route::get('Subtipos', 'SubTypeController@index')->name('subtype.index');
        Route::get('crear/subtipo', 'SubTypeController@create')->name('subtype.create');
        Route::post('subtype/store', 'SubTypeController@store')->name('subtype.store');
        Route::get('/editar/subtipo/{id}', 'SubTypeController@edit')->name('subtype.edit');
        Route::post('subtype/update', 'SubTypeController@update')->name('subtype.update');
        Route::post('subtype/destroy', 'SubTypeController@destroy')->name('subtype.destroy');
        Route::get('/get/subtypes/{type_id}', 'SubTypeController@getSubTypesByType');

        //CATEGORY
        Route::get('/all/categories', 'CategoryController@getCategories');
        Route::get('Categorias', 'CategoryController@index')->name('category.index');
        Route::get('crear/categoria', 'CategoryController@create')->name('category.create');
        Route::post('category/store', 'CategoryController@store')->name('category.store');
        Route::get('/editar/categoria/{id}', 'CategoryController@edit')->name('category.edit');
        Route::post('category/update', 'CategoryController@update')->name('category.update');
        Route::post('category/destroy', 'CategoryController@destroy')->name('category.destroy');
        Route::get('/get/subcategories/{category_id}', 'CategoryController@getSubcategoryByCategory');

        //SUBCATEGORY
        Route::get('/all/subcategories', 'SubcategoryController@getSubcategories');
        Route::get('Subcategorias', 'SubcategoryController@index')->name('subcategory.index');
        Route::get('crear/subcategoria', 'SubcategoryController@create')->name('subcategory.create');
        Route::post('subcategory/store', 'SubcategoryController@store')->name('subcategory.store');
        Route::get('/editar/subcategoria/{id}', 'SubcategoryController@edit')->name('subcategory.edit');
        Route::post('subcategory/update', 'SubcategoryController@update')->name('subcategory.update');
        Route::post('subcategory/destroy', 'SubcategoryController@destroy')->name('subcategory.destroy');

        //EXAMPLER
        Route::get('/all/examplers', 'ExamplerController@getExamplers');
        Route::get('Modelos', 'ExamplerController@index')->name('exampler.index');
        Route::get('crear/modelo', 'ExamplerController@create')->name('exampler.create');
        Route::post('exampler/store', 'ExamplerController@store')->name('exampler.store');
        Route::get('/editar/modelo/{id}', 'ExamplerController@edit')->name('exampler.edit');
        Route::post('exampler/update', 'ExamplerController@update')->name('exampler.update');
        Route::post('exampler/destroy', 'ExamplerController@destroy')->name('exampler.destroy');

        //BRAND
        Route::get('/all/brands', 'BrandController@getBrands');
        Route::get('Marcas', 'BrandController@index')->name('brand.index');
        Route::get('crear/marca', 'BrandController@create')->name('brand.create');
        Route::post('brand/store', 'BrandController@store')->name('brand.store');
        Route::get('/editar/marca/{id}', 'BrandController@edit')->name('brand.edit');
        Route::post('brand/update', 'BrandController@update')->name('brand.update');
        Route::post('brand/destroy', 'BrandController@destroy')->name('brand.destroy');
        Route::get('/get/exampler/{brand_id}', 'BrandController@getJsonBrands');

        //CEDULA
        Route::get('/all/warrants', 'WarrantController@getWarrants');
        Route::get('Cédulas', 'WarrantController@index')->name('warrant.index');
        Route::get('crear/cedula', 'WarrantController@create')->name('warrant.create');
        Route::post('warrant/store', 'WarrantController@store')->name('warrant.store');
        Route::get('/editar/cedula/{id}', 'WarrantController@edit')->name('warrant.edit');
        Route::post('warrant/update', 'WarrantController@update')->name('warrant.update');
        Route::post('warrant/destroy', 'WarrantController@destroy')->name('warrant.destroy');

        //CALIDAD
        Route::get('/all/qualities', 'QualityController@getQualities');
        Route::get('Calidades', 'QualityController@index')->name('quality.index');
        Route::get('crear/calidad', 'QualityController@create')->name('quality.create');
        Route::post('quality/store', 'QualityController@store')->name('quality.store');
        Route::get('/editar/calidad/{id}', 'QualityController@edit')->name('quality.edit');
        Route::post('quality/update', 'QualityController@update')->name('quality.update');
        Route::post('quality/destroy', 'QualityController@destroy')->name('quality.destroy');

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
        Route::get('view/material/items/{id}', 'MaterialController@getItems')->name('material.getItems');
        Route::get('view/material/all/items/{id}', 'MaterialController@getItemsMaterial')->name('material.getItemsMaterial');

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

        //POSITION
        Route::get('ver/posiciones/contenedor/{contenedor}/nivel/{niveles}/anaquel/{anaqueles}/almacen/{almacen}/area/{area}', 'PositionController@index')->name('position.index')
            ->middleware('permission:list_position');
        Route::post('position/store', 'PositionController@store')->name('position.store')
            ->middleware('permission:create_position');
        Route::post('position/update', 'PositionController@update')->name('position.update')
            ->middleware('permission:update_position');
        Route::post('position/destroy', 'PositionController@destroy')->name('position.destroy')
            ->middleware('permission:destroy_position');
        Route::get('/all/positions/{id_container}', 'PositionController@getPositions');

        //LOCATION
        Route::get('ubicaciones', 'LocationController@index')->name('location.index')
            ->middleware('permission:list_location');
        Route::get('/all/locations', 'LocationController@getLocations');
        Route::get('/ver/materiales/ubicacion/{location_id}', 'LocationController@getMaterialsByLocation');
        Route::get('/view/location/all/items/{id}', 'LocationController@getItemsLocation')->name('material.getItemsMaterial');

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

        // TRANSFER
        Route::get('transferencias', 'TransferController@index')->name('transfer.index')
            ->middleware('permission:list_material');
        Route::get('crear/transferencia', 'TransferController@create')->name('transfer.create')
            ->middleware('permission:list_material');
        Route::post('transfer/store', 'TransferController@store')->name('transfer.store')
            ->middleware('permission:list_material');
        Route::get('/get/json/transfer', 'TransferController@getTransfers')
            ->middleware('permission:list_material');
        Route::get('/get/json/transfer/material/{transfer_id}', 'TransferController@getJsonTransfers')
            ->middleware('permission:list_material');
        Route::post('editar/transferencia', 'TransferController@edit')->name('transfer.edit')
            ->middleware('permission:create_material');
        Route::post('transfer/update', 'TransferController@update')->name('transfer.update')
            ->middleware('permission:create_material');
        Route::post('transfer/cancel', 'TransferController@cancel')->name('transfer.cancel')
            ->middleware('permission:create_material');

        Route::get('get/warehouse/area/{area_id}', 'TransferController@getWarehouse')
            ->middleware('permission:list_material');
        Route::get('get/shelf/warehouse/{warehouse_id}', 'TransferController@getShelf')
            ->middleware('permission:list_material');
        Route::get('get/level/shelf/{shelf_id}', 'TransferController@getLevel')
            ->middleware('permission:list_material');
        Route::get('get/container/level/{level_id}', 'TransferController@getContainer')
            ->middleware('permission:list_material');
        Route::get('get/position/container/{container_id}', 'TransferController@getPosition')
            ->middleware('permission:list_material');
    });
});

Route::get('/home', 'HomeController@index')->name('home');
