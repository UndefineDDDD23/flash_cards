<?php

namespace App\Services\Dictionary;

use App\Models\Languages\Language;
use App\Models\Dictionary\Translation;
use App\Models\Dictionary\DictionaryElement;
use App\Contracts\Dictionary\DictionaryServiceInterface;

class DictionaryService implements DictionaryServiceInterface {
    public function saveWord(array $data, Language $toLanguage, Language $fromLanguage): DictionaryElement
    {
        $element = DictionaryElement::create([            
            'language_id'   => $fromLanguage, 
            'description'   => 
                $data['meaning'] . "\n\n" . 
                implode("\n\n", $data['synonyms']) . "\n\n" .
                implode("\n\n", $data['examples']) . "\n\n" .
                $data['how_to_use'], 
            'element_text'  => $data['element_text']
        ]);

        $translation = Translation::create([
            'dictionary_element_id'     => $element->id, 
            'translation_language_id'   => $toLanguage,
            'translated_element_text'   => $data['translated_element_text'], 
            'translated_description'    => 
                $data['translated_meaning'] . "\n\n" . 
                implode("\n\n", $data['translated_synonyms']) . "\n\n" . 
                implode("\n\n", $data['translated_examples']) . "\n\n" . 
                $data['translated_how_to_use']
        ]);

        return $element;
    }
}