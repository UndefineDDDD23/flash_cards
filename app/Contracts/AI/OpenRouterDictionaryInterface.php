<?php

namespace App\Contracts\AI;

use App\Models\Dictionary\DictionaryElement;
use App\Models\Languages\Language;
use App\Services\Dictionary\DictionaryService;

interface OpenRouterDictionaryInterface {
    public function getDictionaryService(): DictionaryService;
    public function generateWordDescription(Language $nativeLanguage, Language $studiedLanguage, string $word): DictionaryElement|bool;
}