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
        Route::get('/all/customers', 'CustomerController@getCustomers')
            ->middleware('permission:list_customer');
        Route::get('clientes', 'CustomerController@index')
            ->name('customer.index')
            ->middleware('permission:list_customer');
        Route::get('crear/cliente', 'CustomerController@create')
            ->name('customer.create')
            ->middleware('permission:create_customer');
        Route::post('customer/store', 'CustomerController@store')
            ->name('customer.store')
            ->middleware('permission:create_customer');
        Route::get('/editar/cliente/{id}', 'CustomerController@edit')
            ->name('customer.edit')
            ->middleware('permission:update_customer');
        Route::post('customer/update', 'CustomerController@update')
            ->name('customer.update')
            ->middleware('permission:update_customer');
        Route::post('customer/destroy', 'CustomerController@destroy')
            ->name('customer.destroy')
            ->middleware('permission:destroy_customer');
        Route::get('clientes/restore', 'CustomerController@indexrestore')
            ->name('customer.indexrestore')
            ->middleware('permission:destroy_customer');
        Route::get('/all/customers/destroy', 'CustomerController@getCustomersDestroy')
            ->middleware('permission:destroy_customer');
        Route::post('customer/restore', 'CustomerController@restore')
            ->name('customer.restore')
            ->middleware('permission:destroy_customer');

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
        Route::get('/all/contacts', 'ContactNameController@getContacts')
            ->middleware('permission:list_contactName');
        Route::get('contactos', 'ContactNameController@index')
            ->name('contactName.index')
            ->middleware('permission:list_contactName');
        Route::get('crear/contacto', 'ContactNameController@create')
            ->name('contactName.create')
            ->middleware('permission:create_contactName');
        Route::post('contact/store', 'ContactNameController@store')
            ->name('contactName.store')
            ->middleware('permission:create_contactName');
        Route::get('/editar/contacto/{id}', 'ContactNameController@edit')
            ->name('contactName.edit')
            ->middleware('permission:update_contactName');
        Route::post('contact/update', 'ContactNameController@update')
            ->name('contactName.update')
            ->middleware('permission:update_contactName');
        Route::post('contact/destroy', 'ContactNameController@destroy')
            ->name('contactName.destroy')
            ->middleware('permission:destroy_contactName');
        Route::get('contactos/restore', 'ContactNameController@indexrestore')
            ->name('contactName.indexrestore')
            ->middleware('permission:destroy_contactName');
        Route::get('/all/contacts/destroy', 'ContactNameController@getContactsDestroy')
            ->middleware('permission:destroy_contactName');
        Route::post('contact/restore', 'ContactNameController@restore')
            ->name('contactName.restore')
            ->middleware('permission:destroy_contactName');

        //MATERIAL TYPE
        Route::get('/all/materialtypes', 'MaterialTypeController@getMaterialTypes')
            ->middleware('permission:list_materialType');
        Route::get('TiposMateriales', 'MaterialTypeController@index')
            ->name('materialtype.index')
            ->middleware('permission:list_materialType');
        Route::get('crear/tipomaterial', 'MaterialTypeController@create')
            ->name('materialtype.create')
            ->middleware('permission:create_materialType');
        Route::post('materialtype/store', 'MaterialTypeController@store')
            ->name('materialtype.store')
            ->middleware('permission:create_materialType');
        Route::get('/editar/tipomaterial/{id}', 'MaterialTypeController@edit')
            ->name('materialtype.edit')
            ->middleware('permission:update_materialType');
        Route::post('materialtype/update', 'MaterialTypeController@update')
            ->name('materialtype.update')
            ->middleware('permission:update_materialType');
        Route::post('materialtype/destroy', 'MaterialTypeController@destroy')
            ->name('materialtype.destroy')
            ->middleware('permission:destroy_materialType');
        Route::get('/get/types/{subcategory_id}', 'MaterialTypeController@getTypesBySubCategory')
            ->middleware('permission:destroy_materialType');

        //SUB TYPE
        Route::get('/all/subtypes', 'SubtypeController@getSubTypes')
            ->middleware('permission:list_subType');
        Route::get('Subtipos', 'SubtypeController@index')
            ->name('subtype.index')
            ->middleware('permission:list_subType');
        Route::get('crear/subtipo', 'SubtypeController@create')
            ->name('subtype.create')
            ->middleware('permission:create_subType');
        Route::post('subtype/store', 'SubtypeController@store')
            ->name('subtype.store')
            ->middleware('permission:create_subType');
        Route::get('/editar/subtipo/{id}', 'SubtypeController@edit')
            ->name('subtype.edit')
            ->middleware('permission:update_subType');
        Route::post('subtype/update', 'SubtypeController@update')
            ->name('subtype.update')
            ->middleware('permission:update_subType');
        Route::post('subtype/destroy', 'SubtypeController@destroy')
            ->name('subtype.destroy')
            ->middleware('permission:destroy_subType');
        Route::get('/get/subtypes/{type_id}', 'SubtypeController@getSubTypesByType');

        //CATEGORY
        Route::get('/all/categories', 'CategoryController@getCategories')
            ->middleware('permission:list_category');
        Route::get('Categorias', 'CategoryController@index')
            ->name('category.index')
            ->middleware('permission:list_category');
        Route::get('crear/categoria', 'CategoryController@create')
            ->name('category.create')
            ->middleware('permission:create_category');
        Route::post('category/store', 'CategoryController@store')
            ->name('category.store')
            ->middleware('permission:create_category');
        Route::get('/editar/categoria/{id}', 'CategoryController@edit')
            ->name('category.edit')
            ->middleware('permission:update_category');
        Route::post('category/update', 'CategoryController@update')
            ->name('category.update')
            ->middleware('permission:update_category');
        Route::post('category/destroy', 'CategoryController@destroy')
            ->name('category.destroy')
            ->middleware('permission:destroy_category');
        Route::get('/get/subcategories/{category_id}', 'CategoryController@getSubcategoryByCategory');

        //SUBCATEGORY
        Route::get('/all/subcategories', 'SubcategoryController@getSubcategories')
            ->middleware('permission:list_subcategory');
        Route::get('Subcategorias', 'SubcategoryController@index')
            ->name('subcategory.index')
            ->middleware('permission:list_subcategory');
        Route::get('crear/subcategoria', 'SubcategoryController@create')
            ->name('subcategory.create')
            ->middleware('permission:create_subcategory');
        Route::post('subcategory/store', 'SubcategoryController@store')
            ->name('subcategory.store')
            ->middleware('permission:create_subcategory');
        Route::get('/editar/subcategoria/{id}', 'SubcategoryController@edit')
            ->name('subcategory.edit')
            ->middleware('permission:update_subcategory');
        Route::post('subcategory/update', 'SubcategoryController@update')
            ->name('subcategory.update')
            ->middleware('permission:update_subcategory');
        Route::post('subcategory/destroy', 'SubcategoryController@destroy')
            ->name('subcategory.destroy')
            ->middleware('permission:destroy_subcategory');

        //EXAMPLER
        Route::get('/all/examplers', 'ExamplerController@getExamplers')
            ->middleware('permission:list_exampler');
        Route::get('Modelos', 'ExamplerController@index')
            ->name('exampler.index')
            ->middleware('permission:list_exampler');
        Route::get('crear/modelo', 'ExamplerController@create')
            ->name('exampler.create')
            ->middleware('permission:create_exampler');
        Route::post('exampler/store', 'ExamplerController@store')
            ->name('exampler.store')
            ->middleware('permission:create_exampler');
        Route::get('/editar/modelo/{id}', 'ExamplerController@edit')
            ->name('exampler.edit')
            ->middleware('permission:update_exampler');
        Route::post('exampler/update', 'ExamplerController@update')
            ->name('exampler.update')
            ->middleware('permission:update_exampler');
        Route::post('exampler/destroy', 'ExamplerController@destroy')
            ->name('exampler.destroy')
            ->middleware('permission:destroy_exampler');

        //BRAND
        Route::get('/all/brands', 'BrandController@getBrands')
            ->middleware('permission:list_brand');
        Route::get('Marcas', 'BrandController@index')
            ->name('brand.index')
            ->middleware('permission:list_brand');
        Route::get('crear/marca', 'BrandController@create')
            ->name('brand.create')
            ->middleware('permission:create_brand');
        Route::post('brand/store', 'BrandController@store')
            ->name('brand.store')
            ->middleware('permission:create_brand');
        Route::get('/editar/marca/{id}', 'BrandController@edit')
            ->name('brand.edit')
            ->middleware('permission:update_brand');
        Route::post('brand/update', 'BrandController@update')
            ->name('brand.update')
            ->middleware('permission:update_brand');
        Route::post('brand/destroy', 'BrandController@destroy')
            ->name('brand.destroy')
            ->middleware('permission:destroy_brand');
        Route::get('/get/exampler/{brand_id}', 'BrandController@getJsonBrands');

        //CEDULA
        Route::get('/all/warrants', 'WarrantController@getWarrants')
            ->middleware('permission:list_warrant');
        Route::get('Cédulas', 'WarrantController@index')
            ->name('warrant.index')
            ->middleware('permission:list_warrant');
        Route::get('crear/cedula', 'WarrantController@create')
            ->name('warrant.create')
            ->middleware('permission:create_warrant');
        Route::post('warrant/store', 'WarrantController@store')
            ->name('warrant.store')
            ->middleware('permission:create_warrant');
        Route::get('/editar/cedula/{id}', 'WarrantController@edit')
            ->name('warrant.edit')
            ->middleware('permission:update_warrant');
        Route::post('warrant/update', 'WarrantController@update')
            ->name('warrant.update')
            ->middleware('permission:update_warrant');
        Route::post('warrant/destroy', 'WarrantController@destroy')
            ->name('warrant.destroy')
            ->middleware('permission:destroy_warrant');

        //CALIDAD
        Route::get('/all/qualities', 'QualityController@getQualities')
            ->middleware('permission:list_quality');
        Route::get('Calidades', 'QualityController@index')
            ->name('quality.index')
            ->middleware('permission:list_quality');
        Route::get('crear/calidad', 'QualityController@create')
            ->name('quality.create')
            ->middleware('permission:create_quality');
        Route::post('quality/store', 'QualityController@store')
            ->name('quality.store')
            ->middleware('permission:create_quality');
        Route::get('/editar/calidad/{id}', 'QualityController@edit')
            ->name('quality.edit')
            ->middleware('permission:update_quality');
        Route::post('quality/update', 'QualityController@update')
            ->name('quality.update')
            ->middleware('permission:update_quality');
        Route::post('quality/destroy', 'QualityController@destroy')
            ->name('quality.destroy')
            ->middleware('permission:destroy_quality');

        //TYPESCRAP
        Route::get('/all/typescraps', 'TypescrapController@getTypeScraps')
            ->middleware('permission:list_typeScrap');
        Route::get('Retacerías', 'TypescrapController@index')
            ->name('typescrap.index')
            ->middleware('permission:list_typeScrap');
        Route::get('crear/retaceria', 'TypescrapController@create')
            ->name('typescrap.create')
            ->middleware('permission:create_typeScrap');
        Route::post('typescrap/store', 'TypescrapController@store')
            ->name('typescrap.store')
            ->middleware('permission:create_typeScrap');
        Route::get('/editar/retaceria/{id}', 'TypescrapController@edit')
            ->name('typescrap.edit')
            ->middleware('permission:update_typeScrap');
        Route::post('typescrap/update', 'TypescrapController@update')
            ->name('typescrap.update')
            ->middleware('permission:update_typeScrap');
        Route::post('typescrap/destroy', 'TypescrapController@destroy')
            ->name('typescrap.destroy')
            ->middleware('permission:destroy_typeScrap');

        //UNITMEASURE
        Route::get('/all/unitmeasure', 'UnitMeasureController@getUnitMeasure')
            ->middleware('permission:list_unitMeasure');
        Route::get('Unidades', 'UnitMeasureController@index')
            ->name('unitmeasure.index')
            ->middleware('permission:list_unitMeasure');
        Route::get('crear/unidad', 'UnitMeasureController@create')
            ->name('unitmeasure.create')
            ->middleware('permission:create_unitMeasure');
        Route::post('unitmeasure/store', 'UnitMeasureController@store')
            ->name('unitmeasure.store')
            ->middleware('permission:create_unitMeasure');
        Route::get('/editar/unidad/{id}', 'UnitMeasureController@edit')
            ->name('unitmeasure.edit')
            ->middleware('permission:update_unitMeasure');
        Route::post('unitmeasure/update', 'UnitMeasureController@update')
            ->name('unitmeasure.update')
            ->middleware('permission:update_unitMeasure');
        Route::post('unitmeasure/destroy', 'UnitMeasureController@destroy')
            ->name('unitmeasure.destroy')
            ->middleware('permission:destroy_unitMeasure');

        //ROL
        Route::get('roles', 'RoleController@index')
            ->name('role.index')
            ->middleware('permission:list_role');
        Route::post('role/store', 'RoleController@store')
            ->name('role.store')
            ->middleware('permission:create_role');
        Route::post('role/update', 'RoleController@update')
            ->name('role.update')
            ->middleware('permission:update_role');
        Route::get('role/permissions/{id}', 'RoleController@getPermissions')
            ->name('role.permissions')
            ->middleware('permission:update_role');
        Route::post('role/destroy', 'RoleController@destroy')
            ->name('role.destroy')
            ->middleware('permission:destroy_role');
        Route::get('/all/roles', 'RoleController@getRoles');
        Route::get('role/permissions/{id}', 'RoleController@getPermissions')
            ->name('role.permissions')
            ->middleware('permission:update_role');
        Route::get('/crear/rol', 'RoleController@create')
            ->name('role.create');
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
            ->middleware('permission:list_entryScrap');
        Route::get('entradas/compra', 'EntryController@indexEntryPurchase')->name('entry.purchase.index')
            ->middleware('permission:list_entryPurchase');
        Route::get('crear/entrada/compra', 'EntryController@createEntryPurchase')->name('entry.purchase.create')
            ->middleware('permission:create_entryPurchase');
        Route::get('entradas/compra/ordenes', 'EntryController@listOrderPurchase')->name('order.purchase.list')
            ->middleware('permission:create_entryPurchase');
        Route::get('crear/entrada/compra/orden/{id}', 'EntryController@createEntryOrder')->name('order.purchase.create')
            ->middleware('permission:create_entryPurchase');
        Route::get('/get/all/orders/entries', 'EntryController@getAllOrders');
        Route::get('/get/order/complete/{code}', 'EntryController@getOrderPurchaseComplete');
        Route::get('imprimir/orden/compra/{id}', 'OrderPurchaseController@printOrderPurchase')
            ->middleware('permission:list_orderPurchaseNormal');
        Route::post('entry/purchase/order/store', 'EntryController@storeEntryPurchaseOrder')->name('entry.purchase.order.store')
            ->middleware('permission:create_entryPurchase');
        Route::get('crear/entrada/retacería', 'EntryController@createEntryScrap')->name('entry.scrap.create')
            ->middleware('permission:create_entryScrap');
        Route::post('entry_purchase/store', 'EntryController@storeEntryPurchase')->name('entry.purchase.store')
            ->middleware('permission:create_entryPurchase');
        Route::post('entry_scrap/store', 'EntryController@storeEntryScrap')->name('entry.scrap.store')
            ->middleware('permission:create_entryScrap');
        Route::get('/get/materials', 'MaterialController@getJsonMaterials');
        Route::get('/get/materials/quote', 'MaterialController@getJsonMaterialsQuote');
        Route::get('/get/materials/scrap', 'MaterialController@getJsonMaterialsScrap');
        Route::get('/get/locations', 'LocationController@getJsonLocations');
        Route::get('/get/items/{id_material}', 'ItemController@getJsonItems');
        Route::get('/get/json/entries/purchase', 'EntryController@getJsonEntriesPurchase');
        Route::get('/get/entries/purchase', 'EntryController@getEntriesPurchase');
        Route::get('/get/json/entries/scrap', 'EntryController@getJsonEntriesScrap');
        Route::get('/get/json/items/{entry_id}', 'ItemController@getJsonItemsEntry');

        Route::get('entrada/compra/editar/{entry}', 'EntryController@editEntryPurchase')->name('entry.purchase.edit')
            ->middleware('permission:update_entryPurchase');
        Route::post('entry_purchase/update', 'EntryController@updateEntryPurchase')->name('entry.purchase.update')
            ->middleware('permission:update_entryPurchase');
        Route::post('entry_purchase/destroy/{entry}', 'EntryController@destroyEntryPurchase')->name('entry.purchase.destroy')
            ->middleware('permission:destroy_entryPurchase');
        Route::post('/destroy/detail/{id_detail}/entry/{id_entry}', 'EntryController@destroyDetailOfEntry')
            ->middleware('permission:destroy_entryPurchase');
        Route::post('/add/materials/entry/{id_entry}', 'EntryController@addDetailOfEntry')
            ->middleware('permission:destroy_entryPurchase');

        // OUTPUT
        Route::get('solicitudes/salida', 'OutputController@indexOutputRequest')
            ->name('output.request.index')
            ->middleware('permission:list_request');
        Route::get('salidas', 'OutputController@indexOutputs')
            ->name('output.confirm')
            ->middleware('permission:list_output');
        Route::get('crear/solicitud/', 'OutputController@createOutputRequest')
            ->name('output.request.create')
            ->middleware('permission:create_request');
        Route::get('crear/solicitud/extra/{output}', 'OutputController@createOutputRequestOrderExtra')
            ->name('output.request.extra.create')
            ->middleware('permission:create_request');
        Route::get('crear/solicitud/orden/{id_quote}', 'OutputController@createOutputRequestOrder')
            ->name('output.request.order.create')
            ->middleware('permission:create_request');
        Route::post('ouput/store', 'OutputController@storeOutput')
            ->name('output.request.store')
            ->middleware('permission:create_request');
        Route::get('/get/users', 'UserController@getUsers2');
        Route::get('/get/items/output/{id_material}', 'ItemController@getJsonItemsOutput');
        Route::post('output_request/store', 'OutputController@storeOutputRequest')
            ->name('output.request.store')
            ->middleware('permission:create_request');
        Route::get('/get/json/output/request', 'OutputController@getOutputRequest');
        Route::get('/get/json/items/output/{output_id}', 'OutputController@getJsonItemsOutputRequest');
        Route::post('output_request/attend', 'OutputController@attendOutputRequest')
            ->name('output.attend')
            ->middleware('permission:attend_request');
        Route::post('output_request/confirm', 'OutputController@confirmOutputRequest')
            ->name('output.confirmed')
            ->middleware('permission:confirm_output');
        Route::post('output_request/delete/total', 'OutputController@destroyTotalOutputRequest')
            ->name('output.request.destroy')
            ->middleware('permission:confirm_output');
        Route::post('/destroy/output/{id_output}/item/{id_item}', 'OutputController@destroyPartialOutputRequest')
            ->middleware('permission:confirm_output');

        // TRANSFER
        Route::get('transferencias', 'TransferController@index')
            ->name('transfer.index')
            ->middleware('permission:list_transfer');
        Route::get('crear/transferencia', 'TransferController@create')
            ->name('transfer.create')
            ->middleware('permission:list_transfer');
        Route::post('transfer/store', 'TransferController@store')
            ->name('transfer.store')
            ->middleware('permission:create_transfer');
        Route::get('/get/json/transfer', 'TransferController@getTransfers');
        Route::get('/get/json/transfer/material/{transfer_id}', 'TransferController@getJsonTransfers');
        Route::post('editar/transferencia', 'TransferController@edit')
            ->name('transfer.edit')
            ->middleware('permission:update_transfer');
        Route::post('transfer/update', 'TransferController@update')
            ->name('transfer.update')
            ->middleware('permission:update_transfer');
        Route::post('transfer/cancel', 'TransferController@cancel')
            ->name('transfer.cancel')
            ->middleware('permission:destroy_transfer');

        Route::get('get/warehouse/area/{area_id}', 'TransferController@getWarehouse')
            ->middleware('permission:create_transfer');
        Route::get('get/shelf/warehouse/{warehouse_id}', 'TransferController@getShelf')
            ->middleware('permission:create_transfer');
        Route::get('get/level/shelf/{shelf_id}', 'TransferController@getLevel')
            ->middleware('permission:create_transfer');
        Route::get('get/container/level/{level_id}', 'TransferController@getContainer')
            ->middleware('permission:create_transfer');
        Route::get('get/position/container/{container_id}', 'TransferController@getPosition')
            ->middleware('permission:create_transfer');

        // COTIZACIONES
        Route::get('cotizaciones', 'QuoteController@index')
            ->name('quote.index')
            ->middleware('permission:list_quote');
        Route::get('crear/cotizacion', 'QuoteController@create')
            ->name('quote.create')
            ->middleware('permission:create_quote');
        Route::get('/select/materials', 'QuoteController@selectMaterials')
            ->middleware('permission:create_quote');
        Route::get('/get/quote/materials', 'QuoteController@getMaterials')
            ->middleware('permission:create_quote');
        Route::get('/get/quote/typeahead', 'QuoteController@getMaterialsTypeahead')
            ->middleware('permission:create_quote');
        Route::get('/select/consumables', 'QuoteController@selectConsumables')
            ->middleware('permission:create_quote');
        Route::get('/get/quote/consumables', 'QuoteController@getConsumables')
            ->middleware('permission:create_quote');
        Route::post('store/quote', 'QuoteController@store')
            ->name('quote.store')
            ->middleware('permission:create_quote');
        Route::get('/all/quotes', 'QuoteController@getAllQuotes');
        Route::get('ver/cotizacion/{quote}', 'QuoteController@show')
            ->name('quote.show')
            ->middleware('permission:list_quote');
        Route::get('editar/cotizacion/{quote}', 'QuoteController@edit')
            ->name('quote.edit')
            ->middleware('permission:update_quote');
        Route::post('update/quote', 'QuoteController@update')
            ->name('quote.update')
            ->middleware('permission:update_quote');
        Route::post('/destroy/quote/{quote}', 'QuoteController@destroy')
            ->name('quote.destroy')
            ->middleware('permission:destroy_quote');
        Route::post('/confirm/quote/{quote}', 'QuoteController@confirm')
            ->name('quote.confirm')
            ->middleware('permission:confirm_quote');
        Route::post('/raise/quote/{quote}/code/{code}', 'QuoteController@raiseQuote')
            ->name('quote.raise.quote')
            ->middleware('permission:confirm_quote');
        Route::post('/destroy/equipment/{id_equipment}/quote/{id_quote}', 'QuoteController@destroyEquipmentOfQuote')
            ->name('quote.destroy.equipment')
            ->middleware('permission:update_quote');
        Route::post('/update/equipment/{id_equipment}/quote/{id_quote}', 'QuoteController@updateEquipmentOfQuote')
            ->name('quote.update.equipment')
            ->middleware('permission:update_quote');
        Route::get('imprimir/cliente/{quote}', 'QuoteController@printQuoteToCustomer')
            ->middleware('permission:list_quote');
        Route::get('imprimir/interno/{quote}', 'QuoteController@printQuoteToInternal')
            ->middleware('permission:list_quote');
        Route::get('elevar/cotizacion', 'QuoteController@raise')
            ->name('quote.raise')
            ->middleware('permission:create_quote');
        Route::get('/all/quotes/confirmed', 'QuoteController@getAllQuotesConfirmed');
        Route::get('cotizar/soles/cotizacion/{quote}', 'QuoteController@quoteInSoles')
            ->name('quote.in.soles')
            ->middleware('permission:confirm_quote');
        Route::post('/quote/in/soles/quote/{quote}', 'QuoteController@saveQuoteInSoles')
            ->name('quote.in.soles')
            ->middleware('permission:confirm_quote');
        Route::get('ajustar/cotizacion/{quote}', 'QuoteController@adjust')
            ->middleware('permission:confirm_quote');
        Route::post('adjust/quote', 'QuoteController@adjustQuote')
            ->name('quote.adjust')
            ->middleware('permission:confirm_quote');
        Route::get('/all/quotes/deleted', 'QuoteController@getAllQuotesDeleted');
        Route::get('cotizaciones/anuladas', 'QuoteController@deleted')
            ->name('quote.deleted')
            ->middleware('permission:destroy_quote');
        Route::post('/renew/quote/{quote}', 'QuoteController@renewQuote')
            ->middleware('permission:destroy_quote');
        Route::get('cotizaciones/finalizadas', 'QuoteController@closed')
            ->name('quote.closed')
            ->middleware('permission:destroy_quote');
        Route::get('/all/quotes/closed', 'QuoteController@getAllQuotesClosed');
        Route::post('/finish/quote/{quote}', 'QuoteController@closeQuote')
            ->middleware('permission:destroy_quote');
        Route::get('/get/contact/{customer}', 'QuoteController@getContactsByCustomer');

        // ORDER EXECUTION
        Route::get('ordenes/ejecución', 'OrderExecutionController@indexOrderExecution')
            ->name('order.execution.index')
            ->middleware('permission:list_orderExecution');
        Route::get('/all/order/execution', 'OrderExecutionController@getAllOrderExecution');

        // ORDER PURCHASE
        Route::get('ordenes/compra/express', 'OrderPurchaseController@indexOrderPurchaseExpress')
            ->name('order.purchase.express.index')
            ->middleware('permission:list_orderPurchaseExpress');
        Route::get('crear/orden/compra/express', 'OrderPurchaseController@createOrderPurchaseExpress')
            ->name('order.purchase.express.create')
            ->middleware('permission:create_orderPurchaseExpress');
        Route::post('store/order/purchase', 'OrderPurchaseController@storeOrderPurchaseExpress')
            ->name('order.purchase.express.store')
            ->middleware('permission:create_orderPurchaseExpress');
        Route::get('/all/order/express', 'OrderPurchaseController@getAllOrderExpress');
        Route::get('editar/orden/compra/express/{id}', 'OrderPurchaseController@editOrderPurchaseExpress')
            ->middleware('permission:update_orderPurchaseExpress');
        Route::post('update/order/purchase', 'OrderPurchaseController@updateOrderPurchaseExpress')
            ->name('order.purchase.express.update')
            ->middleware('permission:update_orderPurchaseExpress');
        Route::post('/destroy/detail/order/purchase/express/{idDetail}/material/{materialId}', 'OrderPurchaseController@destroyDetail')
            ->middleware('permission:destroy_orderPurchaseExpress');
        Route::post('/update/detail/order/purchase/express/{idDetail}', 'OrderPurchaseController@updateDetail')
            ->middleware('permission:update_orderPurchaseExpress');
        Route::get('ver/orden/compra/express/{id}', 'OrderPurchaseController@showOrderPurchaseExpress')
            ->middleware('permission:list_orderPurchaseExpress');
        Route::post('destroy/order/purchase/express/{id}', 'OrderPurchaseController@destroyOrderPurchaseExpress')
            ->middleware('permission:update_orderPurchaseExpress');

        Route::get('ordenes/compra/normal', 'OrderPurchaseController@indexOrderPurchaseNormal')
            ->name('order.purchase.normal.index')
            ->middleware('permission:list_orderPurchaseNormal');
        Route::get('crear/orden/compra/normal', 'OrderPurchaseController@createOrderPurchaseNormal')
            ->name('order.purchase.normal.create')
            ->middleware('permission:create_orderPurchaseNormal');
        Route::post('store/order/purchase/normal', 'OrderPurchaseController@storeOrderPurchaseNormal')
            ->name('order.purchase.normal.store')
            ->middleware('permission:create_orderPurchaseNormal');
        Route::get('/all/order/normal', 'OrderPurchaseController@getAllOrderNormal');
        Route::get('editar/orden/compra/normal/{id}', 'OrderPurchaseController@editOrderPurchaseNormal')
            ->middleware('permission:update_orderPurchaseNormal');
        Route::post('update/order/purchase/normal', 'OrderPurchaseController@updateOrderPurchaseNormal')
            ->name('order.purchase.normal.update')
            ->middleware('permission:update_orderPurchaseNormal');
        Route::post('/destroy/detail/order/purchase/normal/{idDetail}/material/{materialId}', 'OrderPurchaseController@destroyNormalDetail')
            ->middleware('permission:destroy_orderPurchaseNormal');
        Route::post('/update/detail/order/purchase/normal/{idDetail}', 'OrderPurchaseController@updateNormalDetail')
            ->middleware('permission:update_orderPurchaseNormal');
        Route::get('ver/orden/compra/normal/{id}', 'OrderPurchaseController@showOrderPurchaseNormal')
            ->middleware('permission:list_orderPurchaseNormal');
        Route::post('destroy/order/purchase/normal/{id}', 'OrderPurchaseController@destroyOrderPurchaseNormal')
            ->middleware('permission:destroy_orderPurchaseNormal');

        // PROFILE
        Route::get('perfil', 'UserController@profile')
            ->name('user.profile');
        Route::post('change/image/user/{user}', 'UserController@changeImage')
            ->name('user.change.image');
        Route::post('change/settings/user/{user}', 'UserController@changeSettings')
            ->name('user.change.settings');
        Route::post('change/password/user/{user}', 'UserController@changePassword')
            ->name('user.change.password');

        // INVOICE
        Route::get('factura/compra', 'InvoiceController@indexInvoices')->name('invoice.index')
            ->middleware('permission:list_invoice');
        Route::get('crear/factura/compra', 'InvoiceController@createInvoice')->name('invoice.create')
            ->middleware('permission:create_invoice');
        Route::post('invoice/store', 'InvoiceController@storeInvoice')->name('invoice.store')
            ->middleware('permission:create_invoice');

        Route::get('/get/json/invoices/purchase', 'InvoiceController@getJsonInvoices');
        Route::get('/get/invoices/purchase', 'InvoiceController@getInvoices');
        Route::get('/get/invoice/by/id/{id}', 'InvoiceController@getInvoiceById');
        Route::get('/get/service/by/id/{id}', 'InvoiceController@getServiceById');

        Route::get('factura/compra/editar/{entry}', 'InvoiceController@editInvoice')->name('invoice.edit')
            ->middleware('permission:update_invoice');
        Route::post('invoice/update', 'InvoiceController@updateInvoice')->name('invoice.update')
            ->middleware('permission:update_invoice');

        // REPORT
        Route::get('report/amount/items', 'ReportController@amountInWarehouse');
        Route::get('report/excel/amount/stock', 'ReportController@excelAmountStock')->name('report.excel.amount');
        Route::get('report/excel/bd/materials', 'ReportController@excelBDMaterials')->name('report.excel.materials');
        Route::get('report/chart/quote/raised', 'ReportController@chartQuotesDollarsSoles')->name('report.chart.quote.raised');
        Route::get('report/chart/quote/view/{date_start}/{date_end}', 'ReportController@chartQuotesDollarsSolesView')->name('report.chart.quote.raised.view');
        Route::get('report/chart/expense/income', 'ReportController@chartExpensesIncomeDollarsSoles')->name('report.chart.income.expense');
        Route::get('report/chart/income/expense/view/{date_start}/{date_end}', 'ReportController@chartExpensesIncomeDollarsSolesView')->name('report.chart.income.expense.view');
        Route::get('report/chart/utilities', 'ReportController@chartUtilitiesDollarsSoles')->name('report.chart.utilities');
        Route::get('report/chart/utilities/view/{date_start}/{date_end}', 'ReportController@chartUtilitiesDollarsSolesView')->name('report.chart.utilities.view');

        Route::get('reporte/cotizaciones', 'ReportController@quotesReport')
            ->name('report.quote.index')
            ->middleware('permission:quote_report');
        Route::get('reporte/cotizacion/individual/{id}', 'ReportController@quoteIndividualReport')
            ->name('report.quote.individual')
            ->middleware('permission:quoteIndividual_report');
        Route::get('reporte/cotizaciones/resumen', 'ReportController@quoteSummaryReport')
            ->name('report.quote.summary')
            ->middleware('permission:quoteTotal_report');

        // SERVICIOS y ORDENES DE SERVICIOS
        Route::get('/ordenes/servicio', 'OrderServiceController@indexOrderServices')
            ->name('order.service.index')
            ->middleware('permission:list_orderService');
        Route::get('/listar/ordenes/servicio', 'OrderServiceController@listOrderServices')
            ->name('list.order.service.index')
            ->middleware('permission:list_orderService');
        Route::get('ordenes/servicio/crear', 'OrderServiceController@createOrderServices')
            ->name('order.service.create')
            ->middleware('permission:create_orderService');
        Route::get('servicios/', 'OrderServiceController@indexServices')
            ->name('service.index')
            ->middleware('permission:list_service');
        Route::post('order/service/store/', 'OrderServiceController@storeOrderServices')
            ->name('order.service.store')
            ->middleware('permission:create_orderService');
        Route::get('/all/order/services', 'OrderServiceController@getAllOrderService')
            ->middleware('permission:list_orderService');
        Route::get('/all/order/services/regularize', 'OrderServiceController@getAllOrderRegularizeService')
            ->middleware('permission:list_service');
        Route::post('destroy/order/service/{id}', 'OrderServiceController@destroyOrderService')
            ->middleware('permission:delete_orderService');
        Route::get('ver/orden/servicio/{id}', 'OrderServiceController@showOrderService')
            ->middleware('permission:list_orderService');
        Route::get('imprimir/orden/servicio/{id}', 'OrderServiceController@printOrderService')
            ->middleware('permission:list_orderService');
        Route::get('editar/orden/service/{id}', 'OrderServiceController@editOrderService')
            ->middleware('permission:update_orderService');
        Route::post('order/service/update', 'OrderServiceController@updateOrderService')
            ->name('order.service.update')
            ->middleware('permission:update_orderService');
        Route::post('/update/detail/order/service/{idDetail}', 'OrderServiceController@updateDetail')
            ->middleware('permission:update_orderService');
        Route::post('/destroy/detail/order/service/{idDetail}', 'OrderServiceController@destroyDetail')
            ->middleware('permission:delete_orderService');
        Route::get('ingresar/orden/servicio/{id}', 'OrderServiceController@regularizeOrderService')
            ->middleware('permission:regularize_orderService');
        Route::post('order/service/regularize', 'OrderServiceController@regularizePostOrderService')
            ->name('order.service.regularize')
            ->middleware('permission:regularize_orderService');

        // NOTIFICATIONS
        Route::get('/get/notifications', 'NotificationController@getNotifications');
        Route::post('/read/notification/{id_notification}', 'NotificationController@readNotification');
        Route::post('/leer/todas/notificaciones', 'NotificationController@readAllNotifications');

    });
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/api/sunat', function () {
    $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
            'Authorization: Bearer ' . $token
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;

});
