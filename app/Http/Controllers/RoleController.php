<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('access.roles', compact('roles', 'permissions'));
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
}
