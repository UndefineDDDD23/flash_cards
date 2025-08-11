<?php

namespace App\Services\AI;

use App\Contracts\AI\OpenRouterInterface;
use App\Models\Dictionary\DictionaryElement;
use App\Services\AI\OpenRouterMistral;
use App\Models\Languages\Language;
use App\Contracts\AI\OpenRouterDictionaryInterface;
use App\Services\Dictionary\WordTranslationService;

class OpenRouterDictionary implements OpenRouterDictionaryInterface
{
    protected WordTranslationService $wordTranslationService;
    protected OpenRouterInterface $openRouterModel;

    public function __construct(WordTranslationService $wordTranslationService, OpenRouterInterface $openRouterModel) {
        $this->wordTranslationService = $wordTranslationService;
        $this->openRouterModel = $openRouterModel;
    }

    public function getOpenRouterModel(): OpenRouterInterface {
        return $this->openRouterModel;
    }

    public function getWordTranslationService(): WordTranslationService {
        return $this->wordTranslationService;
    }

    protected function isValidJsonAiResult(string $json): bool
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return false;
        }

        return $this->wordTranslationService->isValidDictionaryElementData($data);
    }

    public function generateWordDescription(Language $nativeLanguage, Language $studiedLanguage, string $word): DictionaryElement|bool
    {
        $message = <<<EOT
            Ты лингвист и переводчик.  
            Опиши слово\фразу "{$word}" и верни только JSON строго по шаблону:

            {
            "need_to_save": boolean,            // если слово\фразу нужно сохранить в словарь - true, иначе - false
            "element_text": string,             // слово\фраза на языке {$studiedLanguage->code}
            "translated_element_text": string,  // переведённое слово\фразу на языке {$nativeLanguage->code}
            "meaning": string,                  // полное, развёрнутое описание значения на языке {$studiedLanguage->code}
            "translated_meaning": string,       // полное, развёрнутое описание значения на языке {$nativeLanguage->code}
            "synonyms": {                       // синонимы: ключ — слово на {$studiedLanguage->code}, значение — на том же {$studiedLanguage->code}
                string: string
            },
            "translated_synonyms": {            // синонимы: ключ — слово на {$studiedLanguage->code}, значение — перевод слова на {$nativeLanguage->code}
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
            $response = $this->openRouterModel->runApiQuery(
                message: $message
            );

            $cleanJson = preg_replace('/^```(?:json)?\s*|```$/m', '', trim($response));            

            if(!$this->isValidJsonAiResult($cleanJson)) {
                continue;
            }
            else {
                $json = json_decode($cleanJson, true);
                if(!isset($json['need_to_save']) && $json['need_to_save']) {
                    return false;
                }
                else {
                    return $this->getWordTranslationService()->createDictionaryEntry($json, $nativeLanguage, $studiedLanguage, true);
                }
            }
        }

        return false;
    }
}