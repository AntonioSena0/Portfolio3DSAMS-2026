<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function register(Request $request){

        $data = $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:users'],
            'bio' => ['nullable', 'string', 'max:250'],
            'email' => ['required', 'string', 'max:100', 'unique:users', 'email'],
            'password' => ['required', 'string', 'min:8', 'max:100'],
        ]);

        $user = User::create([
            'username' => $data['username'],
            'bio' => $data['bio'] ?? '',
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        if(!$user){
            return back()->with('error', 'Erro durante a criação da conta');
        }

        Auth::login($user);

        return redirect()->route('home.posts')->with('success', 'Conta criada com sucesso');

    }

    public function login(Request $request){

        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:100'],
            'password' => ['required', 'string', 'max:100']
        ]);

        if(!Auth::attempt($data)){
            return back()->with('error', 'Credenciais inválidas');
        }

        $request->session()->regenerate();

        return redirect()->route('home.posts')->with('success','Login realizado com sucesso');

    }

    public function logout(){

        Auth::logout();

        return redirect()->route('login')->with('success','');

    }

}
