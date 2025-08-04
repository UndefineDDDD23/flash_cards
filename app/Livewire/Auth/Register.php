<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use App\Models\Languages\Language;
use Illuminate\Database\Eloquent\Collection;
use App\Validation\Fields\ValidationEmailField;
use App\Validation\Fields\ValidationPasswordField;
use App\Validation\Fields\ValidationUsernameField;
use App\Validation\Fields\ValidationLanguageCodeField;
use App\Validation\Fields\ValidationPasswordConfirmationField;

class Register extends Component
{
    public $username;
    public $email;
    public $password;
    public $passwordConfirmation;
    public $userNativeLanguageCode;
    public $userStudiedLanguageCode;
    public Collection $languagesCollection;
    private array $validationRules = [];

    protected function getValidationRules(): array
    {
        return [
            new ValidationUsernameField('username', true),
            new ValidationEmailField('email', true),
            new ValidationPasswordField('password', true),
            new ValidationPasswordConfirmationField('password', 'passwordConfirmation'),
            new ValidationLanguageCodeField('userStudiedLanguageCode', true),
            new ValidationLanguageCodeField('userNativeLanguageCode', true),
        ];
    }

    protected function rules() {
        $rules = [];

        foreach ($this->getValidationRules() as $rule) {
            if($rule instanceof ValidationLanguageCodeField && $rule->getFieldName() === 'userStudiedLanguageCode') {
                $rules = array_merge( $rule->rules(['different:userNativeLanguageCode']), $rules );
            }
            if($rule instanceof ValidationEmailField) {
                $rules = array_merge( $rule->rules(['unique:users,email']), $rules );
            }
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

    public function mount() {
        $this->languagesCollection = Language::all();
    }

    public function register() {
        try {
            $this->validate();
            $userNativeLanguage = Language::where('code', $this->userNativeLanguageCode)->firstOrFail();
            $userStudiedLanguage = Language::where('code', $this->userStudiedLanguageCode)->firstOrFail();

            $user = User::create([
                'name'                  => $this->username,
                'email'                 => $this->email,
                'password'              => Hash::make($this->password),
                'studied_language_id'   => $userStudiedLanguage->id, 
                'native_language_id'    => $userNativeLanguage->id
            ]);

            event(new Registered($user));
            Auth::login($user);
            $user->sendEmailVerificationNotification();
            $this->redirect(route('verification.notice'));
        } catch (ValidationException $th) {
            throw $th;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layoutData(['title' => __('pages-content.register')]);
    }
}
