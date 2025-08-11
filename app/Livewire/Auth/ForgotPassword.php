<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use App\Validation\Fields\ValidationEmailField;

/**
 * Livewire component responsible for initiating the password reset process.
 *
 * Validates user email and delegates sending of the reset link to Laravel's
 * password broker. Validation rules/messages are composed via custom
 * Validation Field objects to keep concerns modular.
 */
class ForgotPassword extends Component {
    /**
     * Email address to which the password reset link will be sent.
     *
     * @var string
     */
    public string $email;

    /**
     * Container for composed validation field objects. Kept for parity with
     * the validation composition pattern used across components.
     *
     * @var array<int, mixed>
     */
    private array $validationRules = [];

    /**
     * Build a collection of validation field objects.
     *
     * @return array<int, \App\Validation\Fields\ValidationFieldInterface>
     */
    protected function getValidationRules(): array
    {
        return [
            new ValidationEmailField('email', true),
        ];
    }

    /**
     * Flatten validation rules into the format expected by Livewire/Validator.
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
     * Provide validation error messages for each field.
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
     * Validate input and request a password reset link from the broker.
     * Stores the broker status in the session flash data.
     *
     * @return void
     */
    public function sendResetPasswordLink() {
        $credentials = $this->validate();
        $status = Password::sendResetLink($credentials);

        Session::flash('sent-reset-password-link', $status);
    }

    /**
     * Render component view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layoutData(['title' => 'Forgot password']);
    }
}
