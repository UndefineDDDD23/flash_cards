<?php

namespace App\Validation\Fields;

use App\Contracts\Validation\Fields\ValidationFieldInterface;

class ValidationUsernameField implements ValidationFieldInterface {
    protected int $minStringLength = 2;
    protected string $localizationKey = 'pages-content.username';
    protected string $fieldName;
    protected bool $required;
    protected array $additionalRules = [];
    protected array $additionalMessages = [];

    public function __construct(string $fieldName = 'username', bool $required = true) {
        $this->fieldName = $fieldName;
        $this->required = $required;
    }
    
    public function getFieldName(): string {
        return $this->fieldName;
    }

    public function isRequired(): bool {
        return $this->required;
    }

    public function rules(array $additionalRules = []): array {
        $rules[$this->fieldName] = [ 
            'string',
            'min:' . $this->minStringLength
        ];

        if ($this->isRequired()) {
            array_push($rules[$this->fieldName], 'required');
        }

        $rules[$this->fieldName] = array_merge($rules[$this->fieldName], $additionalRules, $this->additionalRules);

        return $rules;
    }

    public function messages(array $additionalMessages = []): array {
        $fieldNameLocalized = __($this->localizationKey);
        $messages = [
            $this->fieldName . '.string' => __('validation.string', [
                'attribute' => $fieldNameLocalized, 
            ]),
            $this->fieldName . '.min' => __('validation.min.string', [
                'attribute' => $fieldNameLocalized, 
                'min' => $this->minStringLength
            ])
        ]; 

        if($this->isRequired()) {
            $messages[$this->fieldName . '.required'] = __('validation.required', [
                'attribute' => $fieldNameLocalized, 
            ]);
        }

        return array_merge($messages, $additionalMessages, $this->additionalMessages);
    }
}