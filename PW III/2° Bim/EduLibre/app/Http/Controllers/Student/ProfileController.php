<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('student.profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'bio' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only('name', 'email', 'bio');

        if ($request->hasFile('avatar')) {
            // Handle avatar upload
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('student.profile')
            ->with('status', 'Perfil atualizado com sucesso!');
    }

    public function settings()
    {
        $user = Auth::user();
        return view('student.profile.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required_if:password,not_empty'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'email_notifications' => ['boolean'],
            'push_notifications' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only('email_notifications', 'push_notifications');

        if ($request->filled('password')) {
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Senha atual incorreta'])
                    ->withInput();
            }

            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return redirect()->route('student.settings')
            ->with('status', 'Configurações atualizadas com sucesso!');
    }
}