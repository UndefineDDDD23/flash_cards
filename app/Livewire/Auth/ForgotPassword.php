<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use App\Validation\Fields\ValidationEmailField;

class ForgotPassword extends Component
{
    public string $email;
    private array $validationRules = [];

    protected function getValidationRules(): array
    {
        return [
            new ValidationEmailField('email', true),
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

    public function sendResetPasswordLink() {
        $credentials = $this->validate();
        $status = Password::sendResetLink($credentials);

        Session::flash('sent-reset-password-link', $status);
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layoutData(['title' => 'Forgot password']);
    }
}
