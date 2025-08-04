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

class ResetPassword extends Component
{
    public string $token;
    public string $email;
    protected $queryString = ['email'];
    public string $password;
    public string $passwordConfirmation;
    private array $validationRules = [];

    protected function getValidationRules(): array
    {
        return [
            new ValidationPasswordField('password', true),
            new ValidationPasswordConfirmationField('password', 'passwordConfirmation'),
        ];
    }

    protected function rules() {
        $rules = [
            'token' => ['required']
        ];

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

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
