<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfessorActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'professor') {
            abort(403);
        }

        if ($user->status === 'pending') {
            return redirect()->route('student.dashboard')->withErrors(['status' => 'Seu cadastro de professor ainda aguarda aprovação.']);
        }

        if ($user->status === 'blocked') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Sua conta foi bloqueada.']);
        }

        return $next($request);
    }
}
