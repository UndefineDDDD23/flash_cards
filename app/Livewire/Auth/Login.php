<?php

namespace App\Livewire\Auth;

use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use App\Validation\Fields\ValidationEmailField;
use App\Validation\Fields\ValidationPasswordField;

class Login extends Component
{
    public string $email;
    public string $password;
    public bool $remember;
    
    private array $validationRules = [];

    protected function getValidationRules(): array
    {
        return [
            new ValidationEmailField('email', true),
            new ValidationPasswordField('password', true),
        ];
    }

    protected function rules() {
        $rules = [];

        foreach ($this->getValidationRules() as $rule) {
            $rules = array_merge( $rule->rules(), $rules );
        }

        return $rules;
    }

    protected function messages() {
        $messages = [];

        foreach ($this->getValidationRules() as $rule) {
            $messages = array_merge($rule->messages(), $messages);
        }
        
        return $messages;
    }

    public function login() {
        $credentials = $this->validate();
        $throttleKey = 'login-'. $this->throttleKey();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return;
        }

        if(Auth::attempt($credentials, $this->remember)) {
            RateLimiter::clear($throttleKey);
            Session::regenerate();
            $this->redirect(route('welcome'));
        }
        else {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|' . request()->ip());
    }
    public function render()
    {
        return view('livewire.auth.login')
            ->layoutData([
                'title'=> __('pages-content.login')
            ]);
    }
}
