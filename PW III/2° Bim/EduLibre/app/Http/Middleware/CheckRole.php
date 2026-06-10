<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403);
        }

        if ($user->status === 'blocked') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Sua conta foi bloqueada.']);
        }

        return $next($request);
    }
}
