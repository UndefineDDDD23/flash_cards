<?php

namespace App\Livewire\FlashCards;

use App\Models\User;
use Livewire\Component;
use App\Models\Languages\Language;
use App\Models\FlashCards\FlashCard;
use Illuminate\Support\Facades\Auth;
use App\Models\Dictionary\Translation;
use App\Services\AI\OpenRouterMistral;
use App\Contracts\AI\OpenRouterInterface;
use App\Services\AI\OpenRouterDictionary;
use App\Enums\FlashCards\FlashCardStatuses;
use App\Models\Dictionary\DictionaryElement;
use App\Contracts\AI\OpenRouterDictionaryInterface;
use App\Services\Dictionary\WordTranslationService;
use App\Contracts\Dictionary\WordTranslationServiceInterface;

class CreateFlashCard extends Component
{
    public string $studiedLanguageWord;
    public string $userWrittenMeaning;

    public Translation $translation;

    private function generateDescriptionByAI(User $user, WordTranslationServiceInterface $wordTranslationService, OpenRouterDictionaryInterface $openRouterDictionary): bool {
        $dictionaryElement = DictionaryElement::where('element_text', '=', $this->studiedLanguageWord)->first();
        if(!$dictionaryElement) {            
            $dictionaryElement = $openRouterDictionary->generateWordDescription($user->nativeLanguage, $user->studiedLanguage, $this->studiedLanguageWord);
            if(!$dictionaryElement) {
                return false;
            }
        }
        $this->translation = $dictionaryElement->translations()->firstWhere('translation_language_id', $user->nativeLanguage->id);
        return true;
    }

    public function createFlashCard() {
        
        $this->validate([
            'studiedLanguageWord'   => 'required|string|min:2|max:255',
            'userWrittenMeaning'    => 'required|string|min:2|max:1000',
        ]);   

        $this->studiedLanguageWord = trim($this->studiedLanguageWord);      
        
        $user = Auth::user();
        $wordTranslationService = new WordTranslationService();
        $openRouterModel = new OpenRouterMistral();
        $openRouterDictionary = new OpenRouterDictionary($wordTranslationService, $openRouterModel);

        $generatedAiDesriptionStatus = $this->generateDescriptionByAI($user, $wordTranslationService, $openRouterDictionary);

        FlashCard::create([
            'user_id'                       => $user->id,
            'status_id'                     => FlashCardStatuses::READY_TO_LEARN->value,
            'user_meaning_text'             => $this->userWrittenMeaning,
            'user_dictionary_element_text'  => $this->studiedLanguageWord,
            'translation_id'                => $generatedAiDesriptionStatus ? $this->translation->id : null,
        ]);
    }

    public function render()
    {
        return view('livewire.flash-cards.create-flash-card');
    }
}
