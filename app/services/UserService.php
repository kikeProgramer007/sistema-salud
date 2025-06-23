<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService {

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function listarUserByRolView(Request $request){
        if($request->filtro_user === '0')
            $users = $this->userRepository->listarUserAll($request);
        else
            $users = $this->userRepository->listarUserByRol($request);

        $view = view('usuarios.tabla', compact('users'))->render();
        return response()->json(['Data' => $view, 'UsersAll' => $this->userRepository->listarUserAllPrueba($request)]);
    }

    public function listarUserAll(Request $request){
        return $this->userRepository->listarUserAll($request);
    }

}
