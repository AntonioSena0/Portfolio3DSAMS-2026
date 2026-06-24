<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentController extends Controller
{

        /**
     * Exibe o dashboard estudantil com estatísticas e dados recentes.
     */
    public function dashboard(): View
    {
        $student = Auth::user();
        $events = Event::query()->where('target_grade', $student->grade)->orderBy('created_at', 'desc')->get();

        return view('student.dashboard', compact('events'));
    }

}
