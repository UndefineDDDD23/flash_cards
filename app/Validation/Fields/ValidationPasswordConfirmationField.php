<?php

namespace App\Validation\Fields;

use App\Contracts\Validation\Fields\ValidationFieldInterface;

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