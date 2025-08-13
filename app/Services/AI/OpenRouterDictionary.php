<?php

namespace App\Services\AI;

use App\Contracts\AI\OpenRouterInterface;
use App\Models\Dictionary\DictionaryElement;
use App\Services\AI\OpenRouterMistral;
use App\Models\Languages\Language;
use App\Contracts\AI\OpenRouterDictionaryInterface;
use App\Services\Dictionary\WordTranslationService;

/**
 * Class OpenRouterDictionary
 * 
 * Service for interacting with the OpenRouter AI to generate dictionary word descriptions.
 * Uses the OpenRouter model to produce a JSON-formatted dictionary entry
 * based on a provided word/phrase and selected languages.
 */
class OpenRouterDictionary implements OpenRouterDictionaryInterface
{
    /** @var WordTranslationService Service for handling translations and dictionary entry validation */
    protected WordTranslationService $wordTranslationService;

    /** @var OpenRouterInterface OpenRouter model instance for running AI queries */
    protected OpenRouterInterface $openRouterModel;

     /**
     * Constructor.
     *
     * @param WordTranslationService $wordTranslationService Service for processing translations and validation
     * @param OpenRouterInterface    $openRouterModel        AI model used to generate dictionary data
     */
    public function __construct(WordTranslationService $wordTranslationService, OpenRouterInterface $openRouterModel) {
        $this->wordTranslationService = $wordTranslationService;
        $this->openRouterModel = $openRouterModel;
    }

    /**
     * Returns the OpenRouter model instance.
     *
     * @return OpenRouterInterface
     */
    public function getOpenRouterModel(): OpenRouterInterface {
        return $this->openRouterModel;
    }

    /**
     * Returns the WordTranslationService instance.
     *
     * @return WordTranslationService
     */
    public function getWordTranslationService(): WordTranslationService {
        return $this->wordTranslationService;
    }

    /**
     * Validates whether a given string is a valid JSON response from the AI
     * and matches the expected dictionary entry structure.
     *
     * @param string $json JSON string returned by the AI
     * @return bool True if JSON is valid and passes dictionary data validation; otherwise false
     */
    protected function isValidJsonAiResult(string $json): bool
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return false;
        }

        return $this->wordTranslationService->isValidDictionaryElementData($data);
    }

    /**
     * Requests the AI to generate a dictionary description for a given word/phrase.
     * Returns the created DictionaryElement object or false if generation failed.
     *
     * @param Language $nativeLanguage  The user's native language
     * @param Language $studiedLanguage The language being learned
     * @param string   $word            The word or phrase to describe
     * @return DictionaryElement|bool   DictionaryElement instance or false if creation failed
     */
    public function generateWordDescription(Language $nativeLanguage, Language $studiedLanguage, string $word): DictionaryElement|bool
    {
        // AI instruction containing the required JSON output format
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

        $attemptsCount = 1; // Number of retry attempts

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