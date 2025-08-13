<?php

namespace App\Validation\Fields;

use App\Contracts\Validation\Fields\ValidationFieldInterface;

/**
 * Class ValidationEmailField
 * 
 * This class implements the ValidationFieldInterface to provide validation rules
 * and messages for an email field in a form.
 */
class ValidationEmailField implements ValidationFieldInterface {
    protected int $maxStringLength = 255;
    protected string $localizationKey = 'pages-content.email';
    protected string $fieldName;
    protected bool $required;
    protected array $additionalRules = [];
    protected array $additionalMessages = [];
    
    public function __construct(string $fieldName = 'email', bool $required = true) {
        $this->fieldName = $fieldName;
        $this->required = $required;
    }
    
    public function getFieldName(): string {
        return $this->fieldName;
    }

    public function isRequired(): bool {
        return $this->required;
    }

    /**
     * Get the validation rules for the email field.
     *
     * @param array $additionalRules Additional rules to append.
     * @return array
     */
    public function rules(array $additionalRules = []): array {
        $rules[$this->fieldName] = [
            'string',
            'email',
            'max:' . $this->maxStringLength,
        ];

        if ($this->isRequired()) {
            array_push($rules[$this->fieldName], 'required');
        }

        $rules[$this->fieldName] = array_merge($rules[$this->fieldName], $additionalRules, $this->additionalRules);

        return $rules;
    }

    /**
     * Get the validation error messages for the email field.
     *
     * @param array $additionalMessages Additional messages to append.
     * @return array
     */
    public function messages(array $additionalMessages = []): array {
        $fieldNameLocalized = __($this->localizationKey);
        $messages = [
            $this->fieldName . '.string' => __('validation.string', [
                'attribute' => $fieldNameLocalized, 
            ]),
            $this->fieldName . '.max' => __('validation.min.string', [
                'attribute' => $fieldNameLocalized, 
                'max' => $this->maxStringLength,
            ]),
            $this->fieldName . '.email' => __('validation.email', [
                'attribute' => $fieldNameLocalized,
            ]),
        ]; 

        if($this->isRequired()) {
            $messages[$this->fieldName . '.required'] = __('validation.required', [
                'attribute' => $fieldNameLocalized, 
            ]);
        }

        return array_merge($messages, $additionalMessages, $this->additionalMessages);
    }
}