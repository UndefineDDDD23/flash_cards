<?php

namespace App\Contracts\AI;

use App\Models\Dictionary\DictionaryElement;
use App\Models\Languages\Language;
use App\Services\Dictionary\WordTranslationService;

interface OpenRouterDictionaryInterface {
    public function getOpenRouterModel(): OpenRouterInterface;  
    public function getWordTranslationService(): WordTranslationService;
    public function generateWordDescription(Language $nativeLanguage, Language $studiedLanguage, string $word): DictionaryElement|bool;
}