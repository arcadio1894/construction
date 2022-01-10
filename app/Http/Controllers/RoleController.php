<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    const MODULES = [
        'dashboard'=>'DASHBOARD',
        'materialType'=>'TIPO DE MATERIAL',
        'user'=>'USUARIOS',
        'role'=>'ROLES',
        'supplier'=>'PROVEEDORES',
        'permission'=>'PERMISOS',
        'customer'=>'CLIENTES',
        'category'=>'CATEGORÍAS',
        'material'=>'MATERIALES',
        'quote'=>'COTIZACIONES',
        'area'=>'ÁREAS',
        'warehouse'=>'ALMACENES',
        'shelf'=>'ANAQUELES',
        'level'=>'NIVELES',
        'container'=>'CONTENEDORES',
        'location'=>'UBICACIONES',
        'report'=>'REPORTES',
        'email'=>'EMAILS',
        'exampler'=>'MODELOS',
        'brand' => 'MARCAS',
        'subcategory' => 'SUBCATEGORÍAS',
        'subType' => 'SUB TIPOS',
        'warrant' => 'CÉDULA',
        'quality' => 'CALIDAD',
        'position' => 'POSICIONES',
        'unitMeasure' => 'UNIDAD DE MEDIDA',
        'contactName' => 'NOMBRE DE CONTACTO',
        'transfer' => 'TRANSFERENCIAS',
        'entryPurchase' => 'ENTRADA POR COMPRA',
        'entryScrap' => 'RETACERÍA',
        'output' => 'SALIDAS',
        'request' => 'SOLICITUDES',
        'typeScrap' => 'TIPO RETACERÍA',
        'invoice' => 'FACTURAS',
        'orderPurchaseExpress' => 'ORDENES COMPRA EXPRESS',
        'orderPurchaseNormal' => 'ORDENES COMPRA COMUN',
        'orderExecution' => 'ORDENES EJECUCIÓN',
        'orderService' => 'ORDENES DE SERVICIO',
        'service' => 'SERVICIOS',
    ];

    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        $user = Auth::user();
        $permissionsAll = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('access.roles', compact('roles', 'permissions', 'permissionsAll'));
    }

    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();

        //var_dump($request->get('permissions'));

        $role = Role::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]);

        // Sincronizar con los permisos
        $permissions = $request->get('permissions');

        $role->syncPermissions($permissions);

        return response()->json(['message' => 'Rol guardado con éxito.'], 200);

    }

    public function update(UpdateRoleRequest $request)
    {
        $validated = $request->validated();

        $role = Role::findById($request->get('role_id'));

        $role->name = $request->get('name');
        $role->description = $request->get('description');

        $role->save();

        // Sincronizar con los permisos
        $permissions = $request->get('permissions');
        $role->syncPermissions($permissions);

        return response()->json(['message' => 'Rol modificado con éxito.'], 200);

    }

    public function destroy(DeleteRoleRequest $request)
    {
        $validated = $request->validated();

        $role = Role::findById($request->get('role_id'));

        $role->delete();

        return response()->json(['message' => 'Role eliminado con éxito.'], 200);

    }

    public function getPermissions( $id )
    {
        $role = Role::findByName($id);
        //var_dump($role);
        // No usar permissions() sino solo permissions
        $permissionsAll = Permission::all();
        $permissionsSelected = [];
        $permissions = $role->permissions;
        foreach ( $permissions as $permission )
        {
            //var_dump($permission->name);
            array_push($permissionsSelected, $permission->name);
        }
        //var_dump($permissions);
        return array(
            'permissionsAll' => $permissionsAll,
            'permissionsSelected' => $permissionsSelected
        );
    }

    public function getRoles()
    {
        $roles = Role::select('id', 'name', 'description')->get();
        return datatables($roles)->toJson();
    }

    public function create()
    {
        $permissions = Permission::select('id', 'name', 'description')->get();

        $groupPermissions = [];
        $groups = [];
        foreach ( $permissions as $permission )
        {
            $pos = strpos($permission->name, '_');
            $group = substr($permission->name, $pos+1);
            array_push($groupPermissions, $group);
            //array_push($groupPermissions, ['key'=>$group, 'group'=>$this::MODULES[$group]]);
        }
        //dd($groupPermissions);
        $grupos = array_unique($groupPermissions);
        foreach ( $grupos as $group )
        {
            array_push($groups, ['group'=>$group, 'name'=>$this::MODULES[$group]]);
        }
        //dd($groups);
        return view('access.role_create', compact('permissions', 'groups'));
    }

    public function edit( $id )
    {
        $permissions = Permission::select('id', 'name', 'description')->get();

        $groupPermissions = [];
        $groups = [];
        foreach ( $permissions as $permission )
        {
            $pos = strpos($permission->name, '_');
            $group = substr($permission->name, $pos+1);
            array_push($groupPermissions, $group);
            //array_push($groupPermissions, ['key'=>$group, 'group'=>$this::MODULES[$group]]);
        }
        $grupos = array_unique($groupPermissions);
        foreach ( $grupos as $group )
        {
            array_push($groups, ['group'=>$group, 'name'=>$this::MODULES[$group]]);
        }
        //dd(strrpos($permissions[6]->name, $groups[0]['group']));
        $role = Role::findByName($id);

        $permissionsSelected = [];
        $permissions1 = $role->permissions;
        foreach ( $permissions1 as $permission )
        {
            //var_dump($permission->name);
            array_push($permissionsSelected, $permission->name);
        }

        //dd( in_array('holi_dashboard', $permissionsSelected) );
        return view('access.role_edit', compact('permissions', 'groups', 'permissionsSelected', 'role'));
    }
}
