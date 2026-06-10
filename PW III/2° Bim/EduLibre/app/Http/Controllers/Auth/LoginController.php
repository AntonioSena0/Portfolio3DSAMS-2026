<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        if (!Auth::attempt($this->credentials($request), $request->filled('remember'))) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->status === 'blocked') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Sua conta foi bloqueada. Entre em contato com o suporte.']);
        }

        if ($user->role === 'professor' && $user->status === 'pending') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Seu cadastro está aguardando aprovação. Você receberá um e-mail quando for aprovado.']);
        }

        return redirect()->intended($this->redirectPath($user));
    }

    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        throw ValidationException::withMessages([
            'email' => __('auth.throttle'),
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return $request->input('email') . '|' . $request->ip();
    }

    protected function redirectPath($user)
    {
        if ($user->role === 'admin') {
            return '/admin/dashboard';
        }

        if ($user->role === 'professor') {
            return '/professor/dashboard';
        }

        return '/dashboard';
    }
}