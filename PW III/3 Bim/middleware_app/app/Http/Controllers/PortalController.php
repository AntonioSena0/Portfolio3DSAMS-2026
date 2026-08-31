<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SharePortalAccessMessage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\View\View;

class PortalController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [SharePortalAccessMessage::class];
    }

    public function index(): View
    {
        return view('portal');
    }
}
