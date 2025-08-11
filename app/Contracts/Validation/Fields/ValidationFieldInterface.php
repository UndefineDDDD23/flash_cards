<?php

namespace App\Contracts\Validation\Fields;

/**
 * Contract for field-specific validation rule/message providers.
 *
 * Implementations encapsulate validation logic for an individual form field
 * and can be composed by Livewire components or form requests.
 */
interface ValidationFieldInterface {
    /**
     * Build the validation rules array for this field.
     *
     * @param array<int, string> $additionalRules Rules to append for this field
     * @return array<string, array<int, string>> Keyed by field name
     */
    public function rules(array $additionalRules = []): array;

    /**
     * Build the validation messages array for this field.
     *
     * @param array<string, string> $additionalMessages Messages to append
     * @return array<string, string> Fully-qualified rule keys mapped to messages
     */
    public function messages(array $additionalMessages = []): array;

    /**
     * Whether the field is required.
     */
    public function isRequired(): bool;

    /**
     * The field name this validator governs.
     */
    public function getFieldName(): string;
}