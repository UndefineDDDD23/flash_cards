<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Verification\VerificationNotice;
use App\Services\NeuralNetworks\Ollama\LangFileProcessor;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Http;

// Public landing page
Route::get('/', function () {
    return view('pages.welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    // Flash cards area (requires authentication)
    Route::prefix('flash-cards')->name('flash-cards-')->group(function() {
        Route::get('/list', function () {
            return view('pages.flash-cards.flash-cards-panel');
        })->name('panel');

        Route::get('/study', function () {
            return view('pages.flash-cards.study-flash-cards-page');
        })->name('study');
    });
});


require __DIR__.'/auth.php';