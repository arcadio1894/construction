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

        //Permission
        Route::get('permisos', 'PermissionController@index')->name('permission.index')
            ->middleware('permission:list_permission');
        Route::post('permission/store', 'PermissionController@store')->name('permission.store')
            ->middleware('permission:create_permission');
        Route::post('permission/update', 'PermissionController@update')->name('permission.update')
            ->middleware('permission:update_permission');
        Route::post('permission/destroy', 'PermissionController@destroy')->name('permission.destroy')
            ->middleware('permission:destroy_permission');
        Route::get('/all/permissions', 'PermissionController@getPermissions');
    });
});

Route::get('/home', 'HomeController@index')->name('home');
