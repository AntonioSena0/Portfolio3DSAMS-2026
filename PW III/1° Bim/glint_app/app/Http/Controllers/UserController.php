<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function search(Request $request){

        $request->validate([
            'q' => ['nullable', 'string', 'max:200']
        ]);
      
        $term = $request->input('q');
      
        if (empty($term)){
            return collect();
        }
      
        return User::where('username','like','%'.$term.'%')->limit(20)->get();
      
    }

    public function update(Request $request){

        $user = Auth::user();

        $data = $request->validate([
            "username" => ["string", "max:100", Rule::unique("users")->ignore($user->id)],
            "bio" => ["nullable", "string", "max:250"],
        ]);

        $user->update([
            "username"=> $data["username"],
            "bio" => $data["bio"] ?? '',
        ]);

        return redirect()->route("users.profile", $user)->with("success","Perfil atualizado com sucesso");

    }

    public function delete(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        $user->delete();

        return redirect()->route('landing')->with('success', 'Conta deletada com sucesso.');
    }

}
