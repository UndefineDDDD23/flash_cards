<?php

namespace App\Validation\Fields;

use App\Contracts\Validation\Fields\ValidationFieldInterface;

/**
 * Class ValidationPasswordConfirmationField
 * 
 * This class extends the ValidationPasswordField to provide validation rules
 * and messages specifically for a password confirmation field in a form.
 */
class ValidationPasswordConfirmationField extends ValidationPasswordField implements ValidationFieldInterface {
    protected array $additionalRules;    
    protected array $additionalMessages;
    protected bool $required = true;
    protected string $passwordConfirmationLocalizationKey = 'pages-content.password_confirmation';

    public function __construct(string $passwordFieldName = 'password', string $passwordConfirmationFieldName = 'passwordConfirmation') {
        $this->fieldName = $passwordConfirmationFieldName;

        $this->additionalRules = [ 'same:' . $passwordFieldName ];

        $passwordConfirmationFieldNameLocalized = __($this->passwordConfirmationLocalizationKey);
        $passwordFieldNameLocalized = __($this->localizationKey);

        $this->additionalMessages = [
            $this->fieldName . '.same' => __('validation.same', [
                'attribute' => $passwordConfirmationFieldNameLocalized,
                'other'     => $passwordFieldNameLocalized
            ])
        ];
    }
}