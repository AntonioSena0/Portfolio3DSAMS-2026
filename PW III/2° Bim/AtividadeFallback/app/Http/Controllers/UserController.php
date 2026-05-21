<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function store(Request $request){

        $data = $request->validate([
            "name" => ["string", "required", "max:100"],
            "email" => ["string", "required", "max:100", "email", "unique:users"],
            "password" => ["string", "required", "min:8", "max:100"]
        ]);

        $newUser = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"])
        ]);

        if(!$newUser){
            return back()->with("error", "Erro ao cadastrar o usuário");
        }

        Auth::login($newUser);

        return redirect()->route("home")->with("success", "Usuário cadastrado com sucesso");

    }

    public function login(Request $request){

        $data = $request->validate([
            "email" => ["string", "required", "max:100", "email"],
            "password" => ["string", "required", "min:8", "max:100"]
        ]);

        if(!Auth::attempt($data)){
            return back()->with("error", "Credenciais inválidas");
        }

        $request->session()->regenerate();

        return redirect()->route("home")->with("success", "Login realizado com sucesso");

    }

    public function logout(){

        Auth::logout();

        return redirect()->route("login")->with("success", "Logout realizado com sucesso");

    }

}
