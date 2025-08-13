<?php

namespace App\Validation\Fields;

use App\Contracts\Validation\Fields\ValidationFieldInterface;

class ValidationPasswordField implements ValidationFieldInterface {
    protected int $minStringLength = 6;
    protected int $maxStringLength = 12;
    protected string $localizationKey = 'pages-content.password';
    protected string $fieldName;
    protected bool $required;
    protected array $additionalRules = [];
    protected array $additionalMessages = [];

    public function __construct(string $fieldName = 'password', bool $required = true) {
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
     * Get the validation rules for the password field.
     *
     * @param array $additionalRules Additional rules to append.
     * @return array
     */
    public function rules(array $additionalRules = []): array {
        $rules[$this->fieldName] = [  
            'string',
            'min:' . $this->minStringLength,
            'max:' . $this->maxStringLength,
            // 'regex:/[a-z]/',      // хотя бы одна строчная буква
            // 'regex:/[A-Z]/',      // хотя бы одна заглавная буква
            // 'regex:/[0-9]/',      // хотя бы одна цифра
            // 'regex:/[@$!%*#?&]/'  // хотя бы один спецсимвол  // TODO: в проде убрать коментарии и добавить сообщения к данным правилам
        ];

        if ($this->isRequired()) {
            array_push($rules[$this->fieldName], 'required');
        }

        $rules[$this->fieldName] = array_merge($rules[$this->fieldName], $additionalRules, $this->additionalRules);

        return $rules;
    }

    /**
     * Get the validation error messages for the password field.
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
            $this->fieldName . '.min' => __('validation.min.string', [
                'attribute' => $fieldNameLocalized, 
                'min' => $this->minStringLength
            ]),
            $this->fieldName . '.max' => __('validation.max.string', [
                'attribute' => $fieldNameLocalized, 
                'max' => $this->maxStringLength
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