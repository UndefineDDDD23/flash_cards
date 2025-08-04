<?php

namespace App\Livewire\Verification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;

class VerificationNotice extends Component
{
    public function resendVerificationNotification() {
        $user = Auth::user();
        $key = 'livewire-action-'. $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) { // максимум 5 раз в минуту
            return;
        }

        RateLimiter::hit($key, 60); // 60 секунд хранения

        if($user->hasVerifiedEmail()) {
            $this->redirectIntended(route('welcome'));
            return;
        }
        Auth::user()->sendEmailVerificationNotification();
        Session::flash('verification-code-sent', true);
    }

    public function render()
    {
        return view('livewire.verification.verification-notice')
            ->layoutData(['title' => __('pages-content.verification')]);
    }
}
