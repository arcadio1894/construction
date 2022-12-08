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

        Route::get('usuarios/eliminados', 'UserController@indexEnable')->name('user.indexEnable')
            ->middleware('permission:list_user');
        Route::get('/all/users/delete', 'UserController@getUsersDelete');
        Route::post('user/disable', 'UserController@disable')->name('user.disable')
            ->middleware('permission:destroy_user');
        Route::post('user/enable', 'UserController@enable')->name('user.enable')
            ->middleware('permission:destroy_user');

        Route::get('users/to/workers', 'UserController@convertUsersToWorkers')
            ->middleware('permission:list_user');

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

        //CATEGORY INVOICES
        Route::get('/all/categories/invoices', 'CategoryInvoiceController@getCategories')
            ->middleware('permission:list_categoryInvoice');
        Route::get('/categorias/facturas', 'CategoryInvoiceController@index')
            ->name('categoryInvoice.index')
            ->middleware('permission:list_categoryInvoice');
        Route::get('crear/categoria/facturas', 'CategoryInvoiceController@create')
            ->name('categoryInvoice.create')
            ->middleware('permission:create_categoryInvoice');
        Route::post('category/invoice/store', 'CategoryInvoiceController@store')
            ->name('categoryInvoice.store')
            ->middleware('permission:create_categoryInvoice');
        Route::get('/editar/categoria/factura/{id}', 'CategoryInvoiceController@edit')
            ->name('categoryInvoice.edit')
            ->middleware('permission:update_categoryInvoice');
        Route::post('category/invoice/update', 'CategoryInvoiceController@update')
            ->name('categoryInvoice.update')
            ->middleware('permission:update_categoryInvoice');
        Route::post('category/invoice/destroy', 'CategoryInvoiceController@destroy')
            ->name('categoryInvoice.destroy')
            ->middleware('permission:destroy_categoryInvoice');

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
        Route::post('material/enable', 'MaterialController@enableMaterial')->name('material.enable')
            ->middleware('permission:enable_material');
        Route::post('material/disable', 'MaterialController@disableMaterial')->name('material.disable')
            ->middleware('permission:enable_material');
        Route::get('habilitar/materiales', 'MaterialController@indexEnable')->name('material.index.enable')
            ->middleware('permission:enable_material');
        Route::get('/disabled/materials', 'MaterialController@getAllMaterialsDisable')->name('disabled.materials')
            ->middleware('permission:enable_material');

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

        // Reporte de ordenes de compra
        Route::get('exportar/reporte/ordenes/compra', 'OrderPurchaseController@reportOrderPurchase')
            ->name('report.order.purchase')
            ->middleware('permission:create_entryPurchase');

        // Crear retazos en almacen
        Route::get('/crear/retazos/materiales', 'EntryScrapsController@indexScrapsMaterials')
            ->name('entry.create.scrap')
            ->middleware('permission:create_entryScrap');
        Route::get('/get/json/index/materials/scrap', 'EntryScrapsController@getJsonIndexMaterialsScraps');
        Route::get('/ver/items/material/{material_id}', 'EntryScrapsController@showItemsByMaterial')
            ->middleware('permission:create_entryScrap');
        Route::get('/get/json/index/items/material/{material_id}', 'EntryScrapsController@getJsonIndexItemsMaterial');
        Route::post('scrap/store', 'EntryScrapsController@storeScrap')
            ->name('scrap.store')
            ->middleware('permission:create_entryScrap');
        Route::post('store/new/scrap', 'EntryScrapsController@storeNewScrap')
            ->name('store.new.scrap')
            ->middleware('permission:create_entryScrap');
        Route::get('/get/data/material/scrap/{material_id}', 'EntryScrapsController@getJsonDataMaterial');

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
        /*Route::post('ouput/store', 'OutputController@storeOutput')
            ->name('output.request.store')
            ->middleware('permission:create_request');*/
        Route::get('/get/users', 'UserController@getUsers2');
        Route::get('/get/items/output/{id_material}', 'ItemController@getJsonItemsOutput');
        Route::get('/get/items/output/complete/{id_material}', 'ItemController@getJsonItemsOutputComplete');
        Route::get('/get/items/output/scraped/{id_material}', 'ItemController@getJsonItemsOutputScraped');
        Route::post('output_request/store', 'OutputController@storeOutputRequest')
            ->name('output.request.store')
            ->middleware('permission:create_request');
        Route::get('/get/json/output/request', 'OutputController@getOutputRequest');
        Route::get('/get/json/items/output/{output_id}', 'OutputController@getJsonItemsOutputRequest');
        Route::get('/get/json/items/output/devolver/{output_id}', 'OutputController@getJsonItemsOutputRequestDevolver');
        Route::post('output_request/edit/execution', 'OutputController@editOutputExecution')
            ->name('output.edit.execution');

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
        Route::get('/crear/item/personalizado/{id_detail}', 'OutputController@createItemCustom')
            ->name('create.item.custom');
        Route::post('/assign/item/{item_id}/output/detail/{detail_id}', 'OutputController@assignItemToOutputDetail');
        Route::post('/return/output/{id_output}/item/{id_item}', 'OutputController@returnItemOutputDetail');

        Route::post('confirm/outputs/attend', 'OutputController@confirmAllOutputsAttend')
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
        Route::get('cotizaciones/totales', 'QuoteController@indexGeneral')
            ->name('quote.list.general')
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
        Route::get('/all/quotes/general', 'QuoteController@getAllQuotesGeneral');
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
        Route::post('/send/quote/{quote}', 'QuoteController@send')
            ->name('quote.send')
            ->middleware('permission:send_quote');
        Route::post('/raise/quote/{quote}/code/{code}', 'QuoteController@raiseQuote')
            ->name('quote.raise.quote')
            ->middleware('permission:raise_quote');
        Route::post('/destroy/equipment/{id_equipment}/quote/{id_quote}', 'QuoteController@destroyEquipmentOfQuote')
            ->name('quote.destroy.equipment')
            ->middleware('permission:update_quote');
        Route::post('/update/equipment/{id_equipment}/quote/{id_quote}', 'QuoteController@updateEquipmentOfQuote')
            ->name('quote.update.equipment')
            ->middleware('permission:update_quote');
        Route::get('imprimir/cliente/{quote}', 'QuoteController@printQuoteToCustomer')
            ->middleware('permission:printCustomer_quote');
        Route::get('imprimir/interno/{quote}', 'QuoteController@printQuoteToInternal')
            ->middleware('permission:printInternal_quote');
        Route::get('elevar/cotizacion', 'QuoteController@raise')
            ->name('quote.raise')
            ->middleware('permission:showRaised_quote');
        Route::get('/all/quotes/confirmed', 'QuoteController@getAllQuotesConfirmed');
        Route::get('cotizar/soles/cotizacion/{quote}', 'QuoteController@quoteInSoles')
            ->name('quote.in.soles')
            ->middleware('permission:confirm_quote');
        Route::post('/quote/in/soles/quote/{quote}', 'QuoteController@saveQuoteInSoles')
            ->name('quote.in.soles')
            ->middleware('permission:confirm_quote');
        Route::get('ajustar/cotizacion/{quote}', 'QuoteController@adjust')
            ->middleware('permission:adjust_quote');
        Route::post('adjust/quote', 'QuoteController@adjustQuote')
            ->name('quote.adjust')
            ->middleware('permission:adjust_quote');
        Route::get('/all/quotes/deleted', 'QuoteController@getAllQuotesDeleted');
        Route::get('cotizaciones/anuladas', 'QuoteController@deleted')
            ->name('quote.deleted')
            ->middleware('permission:destroy_quote');
        Route::post('/renew/quote/{quote}', 'QuoteController@renewQuote')
            ->middleware('permission:renew_quote');
        Route::get('cotizaciones/finalizadas', 'QuoteController@closed')
            ->name('quote.closed')
            ->middleware('permission:finish_quote');
        Route::get('/all/quotes/closed', 'QuoteController@getAllQuotesClosed');
        Route::post('/finish/quote/{quote}', 'QuoteController@closeQuote')
            ->middleware('permission:finish_quote');
        Route::get('/get/contact/{customer}', 'QuoteController@getContactsByCustomer');

        Route::post('/active/quote/{quote}', 'QuoteController@activeQuote')
            ->middleware('permission:finish_quote');

        Route::post('/deselevar/quote/{quote}', 'QuoteController@deselevarQuote')
            ->middleware('permission:raise_quote');

        Route::get('editar/planos/cotizacion/{quote}', 'QuoteController@editPlanos')
            ->name('quote.edit.planos')
            ->middleware('permission:update_quote');

        Route::post('/modificar/planos/cotizacion/{image}', 'QuoteController@updatePlanos')
            ->middleware('permission:update_quote');
        Route::post('/eliminar/planos/cotizacion/{image}', 'QuoteController@deletePlanos')
            ->middleware('permission:update_quote');
        Route::post('/guardar/planos/cotizacion/{quote}', 'QuoteController@savePlanos')
            ->name('save.planos.quote')
            ->middleware('permission:update_quote');

        // TODO: Cambiar porcentages
        Route::post('/update/percentages/equipment/{id_equipment}/quote/{id_quote}', 'QuoteController@changePercentagesEquipment')
            ->middleware('permission:update_quote');

        Route::post('/adjust/percentages/new/equipment/{id_equipment}/quote/{id_quote}', 'QuoteController@adjustPercentagesEquipment')
            ->middleware('permission:update_quote');

        // TODO: Reemplazar cotizaciones
        Route::get('reemplazar/materiales/cotizacion/{quote}', 'QuoteController@replacement')
            ->middleware('permission:replacement_quote');
        Route::get('/replacement/material/quote/{quote}/equipment/{equipment}/equipmentMaterial/{equipmentMaterial}', 'QuoteController@saveEquipmentMaterialReplacement')
            ->middleware('permission:replacement_quote');
        Route::get('/not/replacement/material/quote/{quote}/equipment/{equipment}/equipmentMaterial/{equipmentMaterial}', 'QuoteController@saveEquipmentMaterialNotReplacement')
            ->middleware('permission:replacement_quote');
        Route::post('/save/replacement/materials/{equipment}/quote/{quote}', 'QuoteController@saveMaterialsReplacementToEquipment')
            ->middleware('permission:replacement_quote');

        // TODO: Finalizar equipos
        Route::get('finalizar/equipos/cotizacion/{quote}', 'QuoteController@finishEquipmentsQuote')
            ->middleware('permission:finishEquipment_quote');
        Route::post('/finish/equipment/{equipment}/quote/{quote}', 'QuoteController@saveFinishEquipmentsQuote')
            ->middleware('permission:finishEquipment_quote');
        Route::post('/enable/equipment/{equipment}/quote/{quote}', 'QuoteController@saveEnableEquipmentsQuote')
            ->middleware('permission:finishEquipment_quote');

        // TODO: Cotizaciones perdidas
        Route::get('cotizaciones/perdidas', 'QuoteController@indexQuoteLost')
            ->name('quote.list.lost')
            ->middleware('permission:list_quote');
        Route::get('/all/quotes/lost', 'QuoteController@getAllQuoteLost');


        // ORDER EXECUTION
        Route::get('ordenes/ejecución', 'OrderExecutionController@indexOrderExecution')
            ->name('order.execution.index')
            ->middleware('permission:list_orderExecution');
        Route::get('/all/order/execution', 'OrderExecutionController@getAllOrderExecution');
        Route::get('ordenes/ejecución/finalizadas', 'OrderExecutionController@indexOrderExecutionFinished')
            ->name('order.execution.finish')
            ->middleware('permission:list_orderExecution');
        Route::get('/all/order/execution/finish', 'OrderExecutionController@getAllOrderExecutionFinished');

        // Ordenes de ejecucion para almacen
        Route::get('/materiales/ordenes/ejecución', 'OrderExecutionController@indexExecutionAlmacen')
            ->name('order.execution.almacen');
            //->middleware('permission:list_orderExecution');
        Route::get('/get/json/materials/quote/almacen/{quote_id}', 'OrderExecutionController@getJsonMaterialsQuoteForAlmacen');
        Route::get('/get/json/materials/order/execution/almacen/{code_execution}', 'OrderExecutionController@getJsonMaterialsByQuoteExecutionForAlmacen');


        // ORDER PURCHASE
        Route::get('ordenes/compra/general', 'OrderPurchaseController@indexOrderPurchaseExpressAndNormal')
            ->name('order.purchase.general.index')
            ->middleware('permission:list_orderPurchaseExpress');
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
        Route::get('/all/order/general', 'OrderPurchaseController@getAllOrderGeneral');
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

        Route::post('order_purchase/change/status/{order_id}/{status}', 'OrderPurchaseController@changeStatusOrderPurchase')
            ->middleware('permission:update_orderPurchaseNormal');

        Route::get('ordenes/compra/eliminadas', 'OrderPurchaseController@indexOrderPurchaseDelete')
            ->name('order.purchase.delete')
            ->middleware('permission:destroy_orderPurchaseNormal');
        Route::get('/all/order/delete', 'OrderPurchaseController@getOrderDeleteGeneral');
        Route::get('ver/orden/compra/eliminada/{id}', 'OrderPurchaseController@showOrderPurchaseDelete')
            ->middleware('permission:list_orderPurchaseExpress');
        Route::get('imprimir/orden/compra/eliminada/{id}', 'OrderPurchaseController@printOrderPurchaseDelete')
            ->middleware('permission:list_orderPurchaseNormal');
        Route::post('/restore/order/purchase/delete/{id}', 'OrderPurchaseController@restoreOrderPurchaseDelete')
            ->middleware('permission:destroy_orderPurchaseNormal');

        Route::get('ordenes/compra/regularizadas', 'OrderPurchaseController@indexOrderPurchaseRegularize')
            ->name('order.purchase.list.regularize')
            ->middleware('permission:list_orderPurchaseNormal');
        Route::get('/all/order/purchase/regularize', 'OrderPurchaseController@getAllOrderRegularize');

        Route::get('ordenes/compra/perdidas', 'OrderPurchaseController@indexOrderPurchaseLost')
            ->name('order.purchase.list.lost')
            ->middleware('permission:list_orderPurchaseNormal');
        Route::get('/all/order/purchase/lost', 'OrderPurchaseController@getAllOrderPurchaseLost');

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
        Route::post('destroy/detail/invoice/{idDetail}', 'InvoiceController@destroyDetailInvoice')
            ->middleware('permission:update_invoice');
        Route::post('destroy/total/invoice/{id}', 'InvoiceController@destroyInvoice')
            ->middleware('permission:destroy_invoice');

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
        Route::get('exportar/reporte/factura', 'InvoiceController@exportInvoices')
            ->middleware('permission:list_invoice');
        Route::get('exportar/reporte/cotizaciones', 'ReportController@exportQuotesExcel')
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

        Route::get('ordenes/servicio/regularizadas', 'OrderServiceController@indexOrderServiceRegularize')
            ->name('order.service.list.regularize')
            ->middleware('permission:list_orderPurchaseNormal');
        Route::get('/all/order/service/regularize', 'OrderServiceController@getAllOrderRegularize');

        Route::get('ordenes/servicio/anuladas', 'OrderServiceController@indexOrderServiceDeleted')
            ->name('order.service.list.deleted')
            ->middleware('permission:list_orderPurchaseNormal');
        Route::get('/all/order/service/deleted', 'OrderServiceController@getAllOrderDeleted');
        Route::get('ordenes/servicio/perdidas', 'OrderServiceController@indexOrderServiceLost')
            ->name('order.service.list.lost')
            ->middleware('permission:list_orderPurchaseNormal');
        Route::get('/all/order/service/lost', 'OrderServiceController@getAllOrderLost');


        // NOTIFICATIONS
        Route::get('/get/notifications', 'NotificationController@getNotifications');
        Route::post('/read/notification/{id_notification}', 'NotificationController@readNotification');
        Route::post('/leer/todas/notificaciones', 'NotificationController@readAllNotifications');

        // CREDITS
        Route::get('/control/creditos', 'SupplierCreditController@indexCredits')
            ->name('index.credit.supplier');
        Route::get('/get/only/invoices/purchase', 'SupplierCreditController@getOnlyInvoicesPurchase');
        Route::get('/get/only/credits/supplier', 'SupplierCreditController@getOnlyCreditsSupplier');
        Route::post('/add/invoice/credit/{idEntry}', 'SupplierCreditController@addInvoiceToCredit');
        Route::get('/get/credit/by/id/{creditId}', 'SupplierCreditController@getCreditById');
        Route::post('credit/control/update', 'SupplierCreditController@update')
            ->name('credit.control.update');
        Route::post('credit/control/paid', 'SupplierCreditController@paid')
            ->name('credit.control.paid');
        Route::post('/cancel/pay/credit/{idCredit}', 'SupplierCreditController@cancelPayCredit');

        // PAYMENT DEADLINES
        Route::get('/all/paymentDeadlines', 'PaymentDeadlineController@getPaymentDeadlines')
            ->middleware('permission:list_paymentDeadline');
        Route::get('plazos/pagos', 'PaymentDeadlineController@index')
            ->name('paymentDeadline.index')
            ->middleware('permission:list_paymentDeadline');
        Route::get('crear/plazo/pago', 'PaymentDeadlineController@create')
            ->name('paymentDeadline.create')
            ->middleware('permission:create_paymentDeadline');
        Route::post('paymentDeadline/store', 'PaymentDeadlineController@store')
            ->name('paymentDeadline.store')
            ->middleware('permission:create_paymentDeadline');
        Route::get('/editar/plazo/pago/{id}', 'PaymentDeadlineController@edit')
            ->name('paymentDeadline.edit')
            ->middleware('permission:update_paymentDeadline');
        Route::post('paymentDeadline/update', 'PaymentDeadlineController@update')
            ->name('paymentDeadline.update')
            ->middleware('permission:update_paymentDeadline');
        Route::post('paymentDeadline/destroy', 'PaymentDeadlineController@destroy')
            ->name('paymentDeadline.destroy')
            ->middleware('permission:destroy_paymentDeadline');

        // FOLLOW MATERIALS
        Route::get('/get/follow/material/{material_id}', 'FollowMaterialController@getFollowMaterial');
        Route::get('/follow/material/{material_id}', 'FollowMaterialController@followMaterial');
        Route::get('/unfollow/material/{material_id}', 'FollowMaterialController@unfollowMaterial');
        Route::get('/seguimiento/materiales', 'FollowMaterialController@index')
            ->name('follow.index')
            ->middleware('permission:list_followMaterials');
        Route::get('/get/json/follow/material', 'FollowMaterialController@getJsonFollowMaterials');
        Route::post('/dejar/seguir/{follow_id}', 'FollowMaterialController@unFollowMaterialUser');

        Route::get('/get/json/follow/output/material/{id}', 'FollowMaterialController@getJsonDetailFollowMaterial');
        Route::get('/visualizar/orden/compra/{code}', 'OrderPurchaseController@showOrderOperator');

        Route::get('/get/json/stock/all/materials', 'FollowMaterialController@getJsonStockAllMaterials');
        Route::get('/alerta/stock/materiales', 'FollowMaterialController@indexStock')
            ->name('stock.index')
            ->middleware('permission:stock_followMaterials');

        Route::get('/send/email/with/excel', 'FollowMaterialController@sendEmailWithExcel');


        // REGULARIZAR AUTOMATICAMENTE ENTRADAS DE COMPRA
        Route::get('/regularizar/automaticamente/entrada/compra/{entry_id}', 'EntryController@regularizeAutoOrderEntryPurchase')
            ->middleware('permission:create_orderPurchaseExpress');
        Route::post('store/regularize/order/purchase', 'EntryController@regularizeEntryToOrderPurchase')
            ->name('order.purchase.regularize.store')
            ->middleware('permission:create_orderPurchaseExpress');

        // REGULARIZAR AUTOMATICAMENTE ENTRADAS DE servicio
        Route::get('/regularizar/automaticamente/entrada/servicio/{entry_id}', 'OrderServiceController@regularizeAutoOrderEntryService')
            ->middleware('permission:create_orderService');
        Route::post('store/regularize/order/service', 'OrderServiceController@regularizeEntryToOrderService')
            ->name('order.service.regularize.store')
            ->middleware('permission:create_orderService');

        // REPORTE DE MATERIALES Y SUS SALIDAS
        Route::get('/reporte/material/salidas', 'OutputController@reportMaterialOutputs')
            ->name('report.materials.outputs')
            ->middleware('permission:report_output');
        Route::get('/get/json/materials/in/output', 'OutputController@getJsonMaterialsInOutput')
            ->middleware('permission:report_output');
        Route::get('/get/json/outputs/of/material/{id_material}', 'OutputController@getJsonOutputsOfMaterial')
            ->middleware('permission:report_output');

        // REPORTE DE MATERIALES Y SUS ENTRADAS
        Route::get('/reporte/material/ingresos', 'EntryController@reportMaterialEntries')
            ->name('report.materials.entries')
            ->middleware('permission:report_output');
        Route::get('/get/json/materials/in/entry', 'EntryController@getJsonMaterialsInEntry')
            ->middleware('permission:report_output');
        Route::get('/get/json/entries/of/material/{id_material}', 'EntryController@getJsonEntriesOfMaterial')
            ->middleware('permission:report_output');

        Route::get('/get/json/quantity/output/material/{id_quote}/{id_material}', 'OutputController@getQuantityMaterialOutputs')
            ->middleware('permission:report_output');

        // SOLICITUD DE COMPRA OPERACION -> LOGISITICA
        Route::get('/solicitud/compra/operaciones', 'RequestPurchaseController@indexRequestPurchase')
            ->name('request.purchase.operator')
            ->middleware('permission:list_requestPurchaseOperator');
        Route::get('/crear/solicitud/compra/operaciones', 'RequestPurchaseController@createRequestPurchase')
            ->name('request.purchase.create.operator')
            ->middleware('permission:create_requestPurchaseOperator');
        Route::post('/store/request/purchase/operator', 'RequestPurchaseController@storeRequestPurchase')
            ->name('request.purchase.store.operator')
            ->middleware('permission:create_requestPurchaseOperator');
        Route::get('/editar/solicitud/compra/operaciones/{id}', 'RequestPurchaseController@editRequestPurchase')
            ->name('request.purchase.edit.operator')
            ->middleware('permission:edit_requestPurchaseOperator');
        Route::post('/update/request/purchase/operator/{id}', 'RequestPurchaseController@updateRequestPurchase')
            ->name('request.purchase.update.operator')
            ->middleware('permission:edit_requestPurchaseOperator');
        Route::post('/delete/request/purchase/operator', 'RequestPurchaseController@destroyRequestPurchase')
            ->name('request.purchase.delete.operator')
            ->middleware('permission:delete_requestPurchaseOperator');

        //PORCENTAGE QUOTES
        Route::get('/all/porcentages/quotes', 'PorcentageQuoteController@getPorcentageQuotes')
            ->middleware('permission:list_porcentageQuote');
        Route::get('porcentajes/cotizaciones', 'PorcentageQuoteController@index')
            ->name('porcentageQuote.index')
            ->middleware('permission:list_porcentageQuote');
        Route::get('crear/porcentaje', 'PorcentageQuoteController@create')
            ->name('porcentageQuote.create')
            ->middleware('permission:create_porcentageQuote');
        Route::post('porcentage/store', 'PorcentageQuoteController@store')
            ->name('porcentageQuote.store')
            ->middleware('permission:create_porcentageQuote');
        Route::get('/editar/porcentaje/cotizacion/{id}', 'PorcentageQuoteController@edit')
            ->name('porcentageQuote.edit')
            ->middleware('permission:update_porcentageQuote');
        Route::post('porcentages/update', 'PorcentageQuoteController@update')
            ->name('porcentageQuote.update')
            ->middleware('permission:update_porcentageQuote');
        Route::post('porcentages/destroy', 'PorcentageQuoteController@destroy')
            ->name('porcentageQuote.destroy')
            ->middleware('permission:destroy_porcentageQuote');

        // REPORTE DE FACTURAS POR CATEGORIAS
        Route::get('/reporte/faturas/finanzas', 'InvoiceController@reportInvoiceFinance')
            ->name('report.invoice.finance')
            ->middleware('permission:list_invoice');
        Route::get('/get/json/invoices/finance', 'InvoiceController@getJsonInvoicesFinance');

        // CRONOGRAMAS DE CONTROL DE HORAS
        Route::get('/cronogramas', 'TimelineController@showTimelines')
            ->name('index.timelines')
            ->middleware('permission:index_timeline');
        /*Route::get('/crear/cronograma', 'TimelineController@createTimelines')
            ->name('create.timeline');*/
        Route::get('/get/timeline/current', 'TimelineController@getTimelineCurrent');
        Route::get('/gestionar/cronograma/{timeline}', 'TimelineController@manageTimeline')
            ->name('manage.timeline');
        Route::get('/ver/cronograma/{timeline}', 'TimelineController@showTimeline')
            ->name('show.timeline');
        Route::get('/registrar/avances/cronograma/{timeline}', 'TimelineController@registerProgressTimeline')
            ->name('register.progress');
        Route::get('/get/timeline/forget/{date}', 'TimelineController@getTimelineForget');
        Route::get('/get/activity/forget/{id_timeline}', 'TimelineController@getActivityForget');


        Route::post('/create/activity/timeline/{id}', 'TimelineController@createNewActivity');
        Route::get('/check/timeline/for/create/{date}', 'TimelineController@checkTimelineForCreate');
        Route::post('/remove/activity/timeline/{id}', 'TimelineController@deleteActivity');
        Route::post('/save/activity/timeline/{id}', 'TimelineController@saveActivity');
        Route::post('/save/progress/activity/{id}', 'TimelineController@saveProgressActivity');
        Route::post('/assign/activity/{activity_id}/timeline/{timeline_id}', 'TimelineController@assignActivityToTimeline');
        Route::get('/print/timeline/{id_timeline}', 'TimelineController@printTimeline')
            ->name('download.timeline');

        // Cambio de Cronogramas
        Route::get('/crear/cronograma/{timeline}', 'TimelineController@createTimeline')
            ->name('create.timeline')
            ->middleware('permission:create_timeline');
        Route::post('/create/work/timeline/{id}', 'TimelineController@createNewWork');
        Route::post('/edit/work/{work_id}/timeline/{timeline_id}', 'TimelineController@editWork');
        Route::post('/create/phase/work/{id}', 'TimelineController@createNewPhase');
        Route::post('/edit/phase/{phase_id}/timeline/{timeline_id}', 'TimelineController@editPhase');
        Route::post('/create/task/phase/{id}', 'TimelineController@createNewTask');
        Route::post('/save/task/timeline/{id}', 'TimelineController@saveTask');
        Route::post('/remove/task/{id}', 'TimelineController@deleteTask');
        Route::post('/remove/phase/{id}', 'TimelineController@deletePhase');
        Route::post('/remove/work/{id}', 'TimelineController@deleteWork');
        Route::get('/revisar/cronograma/{timeline}', 'TimelineController@reviewTimeline')
            ->name('review.timeline')
            ->middleware('permission:show_timeline');
        Route::get('/revisar/avances/cronograma/{timeline}', 'TimelineController@checkProgressTimeline')
            ->name('save.progress')
            ->middleware('permission:progress_timeline');
        Route::post('/save/progress/task/{id}', 'TimelineController@saveProgressTask');
        Route::post('/assign/task/{task_id}/timeline/{timeline_id}', 'TimelineController@assignTaskToTimeline');
        Route::get('/descargar/excel/timeline/{id_timeline}', 'TimelineController@downloadTimeline')
            ->name('excel.timeline')
            ->middleware('permission:download_timeline');
        Route::get('/get/info/work/{id}', 'TimelineController@getInfoWork');


        // TRABAJADORES
        Route::get('/colaboradores', 'WorkerController@index')
            ->name('worker.index')
            ->middleware('permission:list_worker');
        Route::get('/get/workers/', 'WorkerController@getWorkers');
        Route::get('/registrar/colaborador', 'WorkerController@create')
            ->name('worker.create')
            ->middleware('permission:create_worker');
        Route::post('worker/store', 'WorkerController@store')
            ->name('worker.store')
            ->middleware('permission:create_worker');
            /*->middleware('permission:create_material');*/
        Route::get('editar/colaborador/{id}', 'WorkerController@edit')
            ->name('worker.edit')
            ->middleware('permission:edit_worker');
        Route::post('worker/update/{id}', 'WorkerController@update')
            ->name('worker.update')
            ->middleware('permission:edit_worker');
        Route::post('/destroy/worker/{id}', 'WorkerController@destroy')
            ->middleware('permission:destroy_worker');
        Route::get('/habilitar/colaborador', 'WorkerController@indexEnable')
            ->name('worker.enable')
            ->middleware('permission:restore_worker');
        Route::get('/get/workers/enable/', 'WorkerController@getWorkersEnable');
        Route::post('/enable/worker/{id}', 'WorkerController@enable')
            ->middleware('permission:restore_worker');

        // CRUD Contratos
        Route::get('/all/contracts', 'ContractController@getAllContracts')
            ->middleware('permission:contract_worker');
        Route::get('contratos', 'ContractController@index')
            ->name('contract.index')
            ->middleware('permission:contract_worker');
        Route::get('crear/contrato', 'ContractController@create')
            ->name('contract.create')
            ->middleware('permission:contract_worker');
        Route::post('contract/store', 'ContractController@store')
            ->name('contract.store')
            ->middleware('permission:contract_worker');
        Route::get('/editar/contrato/{id}', 'ContractController@edit')
            ->name('contract.edit')
            ->middleware('permission:contract_worker');
        Route::post('contract/update', 'ContractController@update')
            ->name('contract.update')
            ->middleware('permission:contract_worker');
        Route::post('contract/destroy', 'ContractController@destroy')
            ->name('contract.destroy')
            ->middleware('permission:contract_worker');
        Route::get('/all/contracts/deleted', 'ContractController@getContractsDeleted')
            ->middleware('permission:contract_worker');
        Route::get('contratos/eliminados', 'ContractController@indexDeleted')
            ->name('contract.deleted')
            ->middleware('permission:contract_worker');
        Route::post('contract/restore', 'ContractController@restore')
            ->name('contract.restore')
            ->middleware('permission:contract_worker');

        // CRUD Estado Civil
        Route::get('/all/civilStatuses', 'CivilStatusController@getAllCivilStatus')
            ->middleware('permission:statusCivil_worker');
        Route::get('estado/civil', 'CivilStatusController@index')
            ->name('civilStatuses.index')
            ->middleware('permission:statusCivil_worker');
        Route::get('crear/estado/civil', 'CivilStatusController@create')
            ->name('civilStatuses.create')
            ->middleware('permission:statusCivil_worker');
        Route::post('civilStatuses/store', 'CivilStatusController@store')
            ->name('civilStatuses.store')
            ->middleware('permission:statusCivil_worker');
        Route::get('/editar/estado/civil/{id}', 'CivilStatusController@edit')
            ->name('civilStatuses.edit')
            ->middleware('permission:statusCivil_worker');
        Route::post('civilStatuses/update', 'CivilStatusController@update')
            ->name('civilStatuses.update')
            ->middleware('permission:statusCivil_worker');
        Route::post('civilStatuses/destroy', 'CivilStatusController@destroy')
            ->name('civilStatuses.destroy')
            ->middleware('permission:statusCivil_worker');
        Route::get('/all/civilStatuses/deleted', 'CivilStatusController@getCivilStatusesDeleted')
            ->middleware('permission:statusCivil_worker');
        Route::get('estado/civil/eliminados', 'CivilStatusController@indexDeleted')
            ->name('civilStatuses.deleted')
            ->middleware('permission:statusCivil_worker');
        Route::post('civilStatuses/restore', 'CivilStatusController@restore')
            ->name('civilStatuses.restore')
            ->middleware('permission:statusCivil_worker');

        // CRUD Cargos
        Route::get('/all/workFunctions', 'WorkFunctionController@getAllWorkFunctions')
            ->middleware('permission:function_worker');
        Route::get('cargos', 'WorkFunctionController@index')
            ->name('workFunctions.index')
            ->middleware('permission:function_worker');
        Route::get('crear/cargo', 'WorkFunctionController@create')
            ->name('workFunctions.create')
            ->middleware('permission:function_worker');
        Route::post('workFunctions/store', 'WorkFunctionController@store')
            ->name('workFunctions.store')
            ->middleware('permission:function_worker');
        Route::get('/editar/cargo/{id}', 'WorkFunctionController@edit')
            ->name('workFunctions.edit')
            ->middleware('permission:function_worker');
        Route::post('workFunctions/update', 'WorkFunctionController@update')
            ->name('workFunctions.update')
            ->middleware('permission:function_worker');
        Route::post('workFunctions/destroy', 'WorkFunctionController@destroy')
            ->name('workFunctions.destroy')
            ->middleware('permission:function_worker');
        Route::get('/all/workFunctions/deleted', 'WorkFunctionController@getWorkFunctionsDeleted')
            ->middleware('permission:function_worker');
        Route::get('cargos/eliminados', 'WorkFunctionController@indexDeleted')
            ->name('workFunctions.deleted')
            ->middleware('permission:function_worker');
        Route::post('workFunctions/restore', 'WorkFunctionController@restore')
            ->name('workFunctions.restore')
            ->middleware('permission:function_worker');

        // CRUD Sistemas de pension
        Route::get('/all/pensionSystems', 'PensionSystemController@getAllPensionSystems')
            ->middleware('permission:systemPension_worker');
        Route::get('sistemas/pension', 'PensionSystemController@index')
            ->name('pensionSystems.index')
            ->middleware('permission:systemPension_worker');
        Route::get('crear/sistema/pension', 'PensionSystemController@create')
            ->name('pensionSystems.create')
            ->middleware('permission:systemPension_worker');
        Route::post('pensionSystems/store', 'PensionSystemController@store')
            ->name('pensionSystems.store')
            ->middleware('permission:systemPension_worker');
        Route::get('/editar/sistema/pension/{id}', 'PensionSystemController@edit')
            ->name('pensionSystems.edit')
            ->middleware('permission:systemPension_worker');
        Route::post('pensionSystems/update', 'PensionSystemController@update')
            ->name('pensionSystems.update')
            ->middleware('permission:systemPension_worker');
        Route::post('pensionSystems/destroy', 'PensionSystemController@destroy')
            ->name('pensionSystems.destroy')
            ->middleware('permission:systemPension_worker');
        Route::get('/all/pensionSystems/deleted', 'PensionSystemController@getPensionSystemsDeleted')
            ->middleware('permission:systemPension_worker');
        Route::get('sistemas/pension/eliminados', 'PensionSystemController@indexDeleted')
            ->name('pensionSystems.deleted')
            ->middleware('permission:systemPension_worker');
        Route::post('pensionSystems/restore', 'PensionSystemController@restore')
            ->name('pensionSystems.restore')
            ->middleware('permission:systemPension_worker');

        // TODO: Ruta para hacer pruebas en produccion para resolver las cantidades
        Route::get('/prueba/cantidades/', 'OrderPurchaseController@pruebaCantidades');
        Route::get('/prueba/bd/', 'OrderPurchaseController@pruebaBD');
        Route::get('/modificando/takens/', 'OutputController@modificandoMaterialesTomados');

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
