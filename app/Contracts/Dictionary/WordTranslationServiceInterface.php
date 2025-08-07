<?php

namespace App\Contracts\Dictionary;

use App\Models\Languages\Language;
use App\Models\Dictionary\DictionaryElement;

interface WordTranslationServiceInterface {
    public function createDictionaryEntry(array $data, Language $toLanguage, Language $fromLanguage, bool $isAiGenerated = true): DictionaryElement;
    public function formatEntryAsDescription(DictionaryElement $dictionaryElement, Language $translationLanguage): string;
}