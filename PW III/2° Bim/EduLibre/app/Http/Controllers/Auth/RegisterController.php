<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Events\Professor\ProfessorRegistered;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:student,professor'],
            'bio' => ['exclude_unless:role,professor', 'nullable', 'string', 'max:1000'],
            'specialty' => ['exclude_unless:role,professor', 'required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->role === 'professor' ? 'pending' : 'active',
            'bio' => $request->role === 'professor' ? $request->bio : null,
            'specialty' => $request->role === 'professor' ? $request->specialty : null,
        ]);

        if ($request->role === 'professor') {
            event(new ProfessorRegistered($user));
        } else {
            Auth::login($user);
            return redirect()->intended('/dashboard');
        }

        return redirect()->route('login')
            ->with('status', 'Seu cadastro foi realizado com sucesso! Aguarde a aprovação administrativa para acessar a área do professor.');
    }

    protected function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 3)) {
            return;
        }

        throw ValidationException::withMessages([
            'email' => __('auth.throttle'),
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}
