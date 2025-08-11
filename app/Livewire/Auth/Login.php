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

/**
 * Livewire component that authenticates a user with throttling.
 *
 * Combines validation via custom field objects and Laravel's Auth facade.
 * Applies a simple rate limit to mitigate brute-force attempts.
 */
class Login extends Component
{
    /**
     * User email credential.
     *
     * @var string
     */
    public string $email;

    /**
     * User password credential.
     *
     * @var string
     */
    public string $password;

    /**
     * Whether to remember the authenticated session.
     *
     * @var bool
     */
    public bool $remember;
    
    /**
     * Container for validation field objects.
     *
     * @var array<int, mixed>
     */
    private array $validationRules = [];

    /**
     * Build validation fields for email and password.
     *
     * @return array<int, \App\Validation\Fields\ValidationFieldInterface>
     */
    protected function getValidationRules(): array
    {
        return [
            new ValidationEmailField('email', true),
            new ValidationPasswordField('password', true),
        ];
    }

    /**
     * Merge rules from composed validation field objects.
     *
     * @return array<string, mixed>
     */
    protected function rules() {
        $rules = [];

        foreach ($this->getValidationRules() as $rule) {
            $rules = array_merge( $rule->rules(), $rules );
        }

        return $rules;
    }

    /**
     * Aggregate validation messages from field objects.
     *
     * @return array<string, string>
     */
    protected function messages() {
        $messages = [];

        foreach ($this->getValidationRules() as $rule) {
            $messages = array_merge($rule->messages(), $messages);
        }
        
        return $messages;
    }

    /**
     * Attempt to authenticate the user with throttling and remember option.
     * On success, clears throttle, regenerates session, and redirects.
     * On failure, increments throttle and throws a validation exception.
     *
     * @return void
     * @throws ValidationException
     */
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
     * Get the authentication rate limiting throttle key
     * based on email and requester IP address.
     *
     * @return string
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|' . request()->ip());
    }

    /**
     * Render component view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.auth.login')
            ->layoutData([
                'title'=> __('pages-content.login')
            ]);
    }
}
