<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Register;

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
});