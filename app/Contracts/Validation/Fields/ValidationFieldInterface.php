<?php

namespace App\Contracts\Validation\Fields;

interface ValidationFieldInterface {
    public function rules(array $additionalRules = []): array;
    public function messages(array $additionalMessages = []): array;
    public function isRequired(): bool;
    public function getFieldName(): string;
}