<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Validation\Fields\ValidationPasswordField;
use App\Validation\Fields\ValidationPasswordConfirmationField;

/**
 * Livewire component that completes the password reset flow.
 *
 * Accepts token and email (via query string), validates new password, and
 * updates the user's credentials via Laravel's password broker.
 */
class ResetPassword extends Component
{
    /**
     * Password reset token provided in the reset link.
     * @var string
     */
    public string $token;
    /**
     * Email associated with the password reset request.
     * @var string
     */
    public string $email;
    /**
     * Expose email in the query string for deep-linking.
     * @var array<int, string>
     */
    protected $queryString = ['email'];
    /**
     * New password (plain text; hashed before persistence).
     * @var string
     */
    public string $password;
    /**
     * Confirmation of the new password.
     * @var string
     */
    public string $passwordConfirmation;
    /**
     * Container for validation field objects.
     * @var array<int, mixed>
     */
    private array $validationRules = [];

    /**
     * Build validation field objects for password reset.
     *
     * @return array<int, \App\Validation\Fields\ValidationFieldInterface>
     */
    protected function getValidationRules(): array
    {
        return [
            new ValidationPasswordField('password', true),
            new ValidationPasswordConfirmationField('password', 'passwordConfirmation'),
        ];
    }

    /**
     * Compose rules including mandatory token presence.
     *
     * @return array<string, mixed>
     */
    protected function rules() {
        $rules = [
            'token' => ['required']
        ];

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
     * Validate the payload and reset the user's password via the broker.
     * On success, redirect to welcome page; otherwise flash failure state.
     *
     * @return void
     */
    public function resetPassword() {
        $this->validate();

        $status = Password::reset([
                'password' => $this->password, 
                'password_confirmation' => $this->passwordConfirmation, 
                'token' => $this->token, 
                'email' => $this->email, 
        ],
        function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if($status) {
            $this->redirect(route('welcome'));
        }
        else {
            Session::flash('reset-password-link-sent', false);
        }
    }

    /**
     * Render component view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
