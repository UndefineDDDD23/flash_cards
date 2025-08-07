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
            'language_id'   => $fromLanguage->id, 
            'meaning'       => $data['meaning'],
            'how_to_use'    => $data['how_to_use'], 
            'element_text'  => $data['element_text'],
            'synonyms'      => json_encode($data['synonyms']), 
            'examples'      => json_encode($data['examples']), 
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

    public function getCompiledDictionaryElementDescription(DictionaryElement $dictionaryElement, Language $translationLanguage): string
    {
        $translation = $dictionaryElement
            ->translations()
            ->firstWhere('translation_language_id', $translationLanguage->id);

        // Декодируем JSON в ассоциативные массивы
        $synonyms = json_decode($translation->translated_synonyms, true);
        $examples = json_decode($translation->translated_examples, true);

        // Форматируем синонимы с ключами
        $synonymsText = collect($synonyms)
            ->map(fn($value, $key) => "$key — $value")
            ->implode("\n");

        // Форматируем примеры с ключами
        $examplesText = collect($examples)
            ->map(fn($value, $key) => "$key — $value")
            ->implode("\n");

        // Собираем финальный текст
        $description = "Значение:\n" . $translation->translated_meaning . "\n\n" .
                    "Синонимы:\n" . $synonymsText . "\n\n" .
                    "Примеры:\n" . $examplesText . "\n\n" .
                    "Примечания к использованию:\n" . $translation->translated_how_to_use;

        return $description;
    }
}