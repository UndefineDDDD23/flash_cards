<?php

namespace App\Contracts\AI;

use App\Models\Dictionary\DictionaryElement;
use App\Models\Languages\Language;
use App\Services\Dictionary\WordTranslationService;

/**
 * Interface for dictionary functionality using OpenRouter AI services.
 *
 * This interface combines dictionary operations with OpenRouter AI capabilities,
 * allowing for AI-generated dictionary content.
 */
interface OpenRouterDictionaryInterface {
    /**
     * Gets the OpenRouter model instance.
     *
     * @return OpenRouterInterface The OpenRouter service implementation
     */
    public function getOpenRouterModel(): OpenRouterInterface;

    /**
     * Gets the word translation service instance.
     *
     * @return WordTranslationService The dictionary service implementation
     */
    public function getWordTranslationService(): WordTranslationService;

    /**
     * Generates a description for a given word using AI.
     *
     * @param Language $nativeLanguage The user's native language
     * @param Language $studiedLanguage The language being studied
     * @param string $word The word to generate description for
     * @return DictionaryElement|bool The generated dictionary element or false on failure
     */
    public function generateWordDescription(
        Language $nativeLanguage,
        Language $studiedLanguage,
        string $word
    ): DictionaryElement|bool;
}