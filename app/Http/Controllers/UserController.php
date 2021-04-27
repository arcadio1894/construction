<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();

        return view('access.users', compact('users', 'roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $request->get('name'),
            'dni' => $request->get('dni'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('dni')),
        ]);

        // Sincronizar con roles
        $roles = $request->get('roles');
        //var_dump($roles);
        $user->syncRoles($roles);

        // TODO: Tratamiento de un archivo de forma tradicional
        if (!$request->file('image')) {
            $user->image = 'no_image.jpg';
            $user->save();
        } else {
            $path = public_path().'/images/user/';
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = $user->id . '.' . $extension;
            $request->file('image')->move($path, $filename);
            $user->image = $filename;
            $user->save();
        }

        return response()->json(['message' => 'Usuario guardado con éxito.'], 200);

    }

    public function update(UpdateUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::find($request->get('user_id'));

        $user->name = $request->get('name');
        $user->dni = $request->get('dni');
        $user->email = $request->get('email');
        $user->save();

        // Sincronizar con roles
        $roles = $request->get('roles');
        $user->syncRoles($roles);

        // TODO: Tratamiento de un archivo de forma tradicional
        if (!$request->file('image')) {
            if ($user->image == 'no_image.jpg' || $user->image == null) {
                $user->image = 'no_image.jpg';
                $user->save();
            }

        } else {
            $path = public_path().'/images/user/';
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = $user->id . '.' . $extension;
            $request->file('image')->move($path, $filename);
            $user->image = $filename;
            $user->save();
        }

        return response()->json(['message' => 'Usuario modificado con éxito.'], 200);

    }

    public function destroy(DeleteUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::find($request->get('user_id'));

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito.'], 200);

    }

    public function getRoles( $id )
    {
        $user = User::find($id);
        //var_dump($role);
        // No usar permissions() sino solo permissions
        $rolesAll = Role::all();
        $rolesSelected = [];
        $roles = $user->roles;
        foreach ( $roles as $role )
        {
            //var_dump($permission->name);
            array_push($rolesSelected, $role->name);
        }
        //var_dump($permissions);
        return array(
            'rolesAll' => $rolesAll,
            'rolesSelected' => $rolesSelected
        );
    }
}
