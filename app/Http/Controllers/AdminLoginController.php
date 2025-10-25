<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AdminLoginController
{
    public function store(Request $request)
    {
        // Accept common field names used by Filament/Livewire login
        $email = $request->input('email') ?? $request->input('login') ?? $request->input('username');
        $password = $request->input('password');

        $request->merge(['email' => $email, 'password' => $password]);

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (Auth::guard('web')->attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            // Redirect to Filament dashboard or admin home
            return Redirect::intended('/admin');
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }
}
