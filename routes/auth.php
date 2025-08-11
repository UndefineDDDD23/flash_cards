<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Verification\VerificationNotice;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Guest-only routes for registration and authentication
Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');

    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Authenticated-only routes for verification
Route::middleware('auth')->group(function () {
    Route::get('/verification/notice', VerificationNotice::class)->name('verification.notice');
    Route::get('/verification/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect(route('welcome'));
    })->middleware(['auth', 'signed'])->name('verification.verify');
});
