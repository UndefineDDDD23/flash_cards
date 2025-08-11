<?php

namespace App\Livewire\Verification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Livewire Component for handling email verification notices.
 */
class VerificationNotice extends Component
{
    /**
     * Resend the email verification notification to the user.
     *
     * Rate limited to prevent abuse (max 5 attempts per minute).
     * Redirects to welcome page if email is already verified.
     */
    public function resendVerificationNotification() {
        $user = Auth::user();
        $key = 'verification-send-notification-'. $user->id;

        // Check if user has exceeded rate limit for verification emails
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return;
        }

        // Record the rate limiting attempt and set expiration (60 seconds)
        RateLimiter::hit($key, 60); // 60 seconds retention

        if($user->hasVerifiedEmail()) {
            // If already verified, redirect to welcome page
            $this->redirectIntended(route('welcome'));
            return;
        }
        Auth::user()->sendEmailVerificationNotification();
        Session::flash('verification-code-sent', true);
    }

    /**
     * Render the verification notice view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.verification.verification-notice')
            ->layoutData(['title' => __('pages-content.verification')]);
    }
}
