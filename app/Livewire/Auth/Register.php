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

/**
 * Livewire component that handles user registration.
 *
 * Validates credentials and language selections, creates a user, fires the
 * Registered event, logs the user in, and triggers email verification.
 */
class Register extends Component
{
    /**
     * Desired username.
     * @var string
     */
    public $username;
    /**
     * Registration email.
     * @var string
     */
    public $email;
    /**
     * Registration password (plain text; hashed before persistence).
     * @var string
     */
    public $password;
    /**
     * Confirmation of the registration password.
     * @var string
     */
    public $passwordConfirmation;
    /**
     * User's native language code (ISO-like code).
     * @var string
     */
    public $userNativeLanguageCode;
    /**
     * User's studied language code (must differ from native).
     * @var string
     */
    public $userStudiedLanguageCode;
    /**
     * Available languages for selection.
     * @var Collection<int, \App\Models\Languages\Language>
     */
    public Collection $languagesCollection;
    /**
     * Container for validation field objects.
     * @var array<int, mixed>
     */
    private array $validationRules = [];

    /**
     * Build validation field objects for registration form.
     *
     * @return array<int, \App\Validation\Fields\ValidationFieldInterface>
     */
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

    /**
     * Compose the validation rules array, applying additional constraints:
     * - studied language must differ from native language
     * - email must be unique in users table
     *
     * @return array<string, mixed>
     */
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

    /**
     * Aggregate translated validation messages.
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
     * Load available languages for the registration form.
     *
     * @return void
     */
    public function mount() {
        $this->languagesCollection = Language::all();
    }

    /**
     * Validate input, create a new user, authenticate, and start email
     * verification flow. Errors are logged; validation exceptions are surfaced
     * to the form.
     *
     * @return void
     * @throws ValidationException
     */
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
