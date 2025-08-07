<?php

namespace App\Contracts\Dictionary;

use App\Models\Languages\Language;
use App\Models\Dictionary\DictionaryElement;

interface WordTranslationServiceInterface {
    public function createDictionaryEntry(array $data, Language $toLanguage, Language $fromLanguage): DictionaryElement;
    public function formatEntryAsDescription(DictionaryElement $dictionaryElement, Language $translationLanguage): string;
}