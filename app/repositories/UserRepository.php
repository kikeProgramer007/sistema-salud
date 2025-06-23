<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRepository {

    public function listarUserByRol(Request $request){
        if (isset($request->buscador) && strlen($request->buscador) > 0)
            $users = User::listadoByRol($request);
        else {
            $rol = Role::find($request->filtro_user);
            $users = $rol->users()->orderBy('name', 'asc')->paginate(10);
        }
        return $users;
    }

    public function listarUserAll(Request $request){
        if (isset($request->buscador) && strlen($request->buscador) > 0)
            $users = User::listadoGeneral($request);
        else
            $users = self::getUserAll();
        return $users;
    }

    public function listarUserAllPrueba(Request $request){
        if (isset($request->buscador) && strlen($request->buscador) > 0)
            $users = User::listadoGeneralGet($request);
        else
            $users = self::getUserAll();
        return $users;
    }

    public function getUserAll(){
        $users = User::orderBy('name', 'asc')->paginate(10);
        return $users;
    }
}
