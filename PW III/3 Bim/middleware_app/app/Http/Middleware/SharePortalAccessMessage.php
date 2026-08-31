<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SharePortalAccessMessage
{
    public function handle(Request $request, Closure $next): Response
    {
        View::share('portalMessages', [
            'Bem vindo ao portal',
            'Seu acesso não foi autorizado.',
            'Entrar em contato com o administrador.',
        ]);

        return $next($request);
    }
}
