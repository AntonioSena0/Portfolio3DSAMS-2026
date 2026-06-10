<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserNotBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status === 'blocked') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Sua conta foi bloqueada.']);
        }

        return $next($request);
    }
}
