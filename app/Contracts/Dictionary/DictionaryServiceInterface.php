<?php

namespace App\Contracts\Dictionary;

use App\Models\Languages\Language;
use App\Models\Dictionary\DictionaryElement;

interface DictionaryServiceInterface {
    public function saveWord(array $data, Language $toLanguage, Language $fromLanguage): DictionaryElement;
}