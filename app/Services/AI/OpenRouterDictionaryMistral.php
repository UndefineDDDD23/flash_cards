<?php

namespace App\Services\AI;

use App\Contracts\AI\OpenRouterInterface;
use App\Models\Dictionary\DictionaryElement;
use App\Services\AI\OpenRouter;
use App\Models\Languages\Language;
use App\Contracts\AI\OpenRouterDictionaryInterface;
use App\Services\Dictionary\DictionaryService;

class OpenRouterDictionaryMistral extends OpenRouter implements OpenRouterDictionaryInterface, OpenRouterInterface
{
    protected string $model = 'mistralai/mistral-small-3.2-24b-instruct:free';
    protected DictionaryService $dictionaryService;

    public function __construct(DictionaryService $dictionaryService) {
        $this->dictionaryService = $dictionaryService;
    }

    public function getDictionaryService(): DictionaryService {
        return $this->dictionaryService;
    }

    protected function isValidJsonAiResult(string $json): bool
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return false; // невалидный JSON
        }

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

        return true; // всё прошло
    }

    public function generateWordDescription(Language $nativeLanguage, Language $studiedLanguage, string $word): DictionaryElement|bool
    {
        $message = <<<EOT
            Ты лингвист и переводчик.  
            Опиши слово "{$word}" и верни только JSON строго по шаблону:

            {
            "element_text": string,             // слово на языке {$studiedLanguage->code}
            "translated_element_text": string,  // переведённое слово на языке {$nativeLanguage->code}
            "meaning": string,                  // полное, развёрнутое описание значения на языке {$studiedLanguage->code}
            "translated_meaning": string,       // полное, развёрнутое описание значения на языке {$nativeLanguage->code}
            "synonyms": {                       // синонимы: ключ — слово на {$studiedLanguage->code}, значение — перевод на {$studiedLanguage->code}
                string: string
            },
            "translated_synonyms": {            // синонимы: ключ — слово на {$studiedLanguage->code}, значение — описание слова на {$nativeLanguage->code}
                string: string
            },
            "examples": {                       // примеры использования: ключ — пример на {$studiedLanguage->code}, значение — на {$studiedLanguage->code}
                string: string
            },
            "translated_examples": {            // примеры использования: ключ — пример на {$studiedLanguage->code}, значение — перевод на {$nativeLanguage->code}
                string: string
            },
            "how_to_use": string                // пояснения на {$studiedLanguage->code}
            },
            "translated_how_to_use": string     // пояснения на {$nativeLanguage->code}
            }

            ⚠️ Возвращай только JSON, без пояснений, без Markdown-блоков.
        EOT;

        $attemptsCount = 1;

        for ($attempt=1; $attempt <= $attemptsCount; $attempt++) { 
            $response = $this->runApiQuery(
                message: $message,
                model: $this->model
            );

            $cleanJson = preg_replace('/^```(?:json)?\s*|```$/m', '', trim($response));            

            if(!$this->isValidJsonAiResult($cleanJson)) {
                continue;
            }
            else {
                $json = json_decode($cleanJson, true);
                return $this->getDictionaryService()->saveWord($json, $nativeLanguage, $studiedLanguage);
            }
        }

        return false;
    }
}