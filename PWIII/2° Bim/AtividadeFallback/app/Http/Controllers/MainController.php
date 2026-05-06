<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{

    public function register(){

        if(auth()->user()){
            return redirect()->route("home");
        }

        return view("register");
    }

    public function login(){

        if(auth()->user()){
            return redirect()->route("home");
        }

        return view("login");
    }

    public function home(){

        $tasks = auth()->user()->tasks()->get();

        return view("home", compact("tasks"));
    }

    public function fallback(){
        return view("fallback");
    }

}
