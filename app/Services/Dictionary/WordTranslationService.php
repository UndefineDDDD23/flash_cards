<?php

namespace App\Services\Dictionary;

use App\Models\Languages\Language;
use App\Models\Dictionary\Translation;
use App\Models\Dictionary\DictionaryElement;
use App\Contracts\Dictionary\WordTranslationServiceInterface;

class WordTranslationService implements WordTranslationServiceInterface {
    public function isValidDictionaryElementData(array $data): bool
    {
        // список обязательных ключей
        $requiredKeys = [
            'element_text',
            'translated_element_text',
            'meaning',
            'translated_meaning',
            'synonyms',
            'translated_synonyms',
            'examples',
            'translated_examples',
            'how_to_use',
            'translated_how_to_use',
        ];

        // проверка наличия всех ключей
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                return false;
            }
        }

        // проверка строковых полей
        $stringFields = [
            'element_text',
            'translated_element_text',
            'meaning',
            'translated_meaning',
            'how_to_use',
            'translated_how_to_use',
        ];
        foreach ($stringFields as $field) {
            if (!is_string($data[$field]) || trim($data[$field]) === '') {
                return false;
            }
        }

        // проверка массивов-словари (ассоциативные массивы)
        $dictFields = ['synonyms', 'translated_synonyms', 'examples', 'translated_examples'];
        foreach ($dictFields as $field) {
            if (!is_array($data[$field]) || empty($data[$field])) {
                return false;
            }

            foreach ($data[$field] as $key => $value) {
                if (!is_string($key) || trim($key) === '' || !is_string($value) || trim($value) === '') {
                    return false;
                }
            }
        }

        return true;
    }
    public function createDictionaryEntry(array $data, Language $toLanguage, Language $fromLanguage, bool $isAiGenerated = false): DictionaryElement
    {        
        if(!$this->isValidDictionaryElementData($data)) {
            throw new \InvalidArgumentException('Invalid dictionary element data provided.');
        }

        $element = DictionaryElement::create([            
            'language_id'   => $fromLanguage->id, 
            'meaning'       => $data['meaning'],
            'how_to_use'    => $data['how_to_use'], 
            'element_text'  => $data['element_text'],
            'synonyms'      => json_encode($data['synonyms']), 
            'examples'      => json_encode($data['examples']), 
            'is_ai_generated' => $isAiGenerated
        ]);

        $translation = Translation::create([
            'dictionary_element_id'     => $element->id, 
            'translation_language_id'   => $toLanguage->id,
            'translated_element_text'   => $data['translated_element_text'], 
            'translated_meaning'        => $data['translated_meaning'],
            'translated_how_to_use'     => $data['translated_how_to_use'], 
            'translated_synonyms'       => json_encode($data['translated_synonyms']), 
            'translated_examples'       => json_encode($data['translated_examples']), 
        ]);

        return $element;
    }
}