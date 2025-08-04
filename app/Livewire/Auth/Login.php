<?php

namespace App\Livewire\Auth;

use App\Validation\Fields\ValidationPasswordField;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Validation\Fields\ValidationEmailField;

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
        dd($credentials, $this->remember);
        // if(Auth::attempt($credentials, $this->remember)) {
        //     Session::regenerate();

        //     $this->redirect(route('welcome'));
        // }
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layoutData([
                'title'=> __('pages-content.login')
            ]);
    }
}
