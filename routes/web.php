<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return redirect('/admin');
});

// Fallback POST handler for Filament login when JS/Livewire falls back to a classic form POST.
// This complements Filament's GET login route and prevents 405 Method Not Allowed on /admin/login.
Route::post('/admin/login', [AdminLoginController::class, 'store'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->middleware('throttle:10,1')
    ->name('admin.login.post');
