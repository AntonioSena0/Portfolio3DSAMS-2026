<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{

    use AuthorizesRequests;

    public function store(Request $request){

        $data = $request->validate([
            "title" => ["string", "required", "max:100"],
            "end_at" => ["date", "required"]
        ]);

        $newTask = auth()->user()->tasks()->create($data);

        if(!$newTask){
            return back()->with("error", "Erro ao criar task");
        }

        return back()->with("success", "Sucesso ao criar task");

    }

    public function toggle(Task $task){

        $this->authorize('update', $task);

        $task->update(['is_complete' => !$task->is_complete]);

        return back()->with("success", "Tarefa atualizada");

    }

}
