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
    
    public function mount() {
    }

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
        $this->studiedLanguageWord = trim($this->studiedLanguageWord);
        
        $this->validate([
            'studiedLanguageWord' => 'required|string|max:255',
            'userWrittenMeaning' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $wordTranslationService = new WordTranslationService();
        $openRouterModel = new OpenRouterMistral();
        $openRouterDictionary = new OpenRouterDictionary($wordTranslationService, $openRouterModel);

        $generatedAiDesriptionStatus = $this->generateDescriptionByAI($user, $wordTranslationService, $openRouterDictionary);

        FlashCard::create([
            'user_id' => $user->id,
            'status_id' => FlashCardStatuses::READY_TO_LEARN->value,
            'user_text' => $this->userWrittenMeaning,
            'translation_id' => $generatedAiDesriptionStatus ? $this->translation->id : null,
        ]);
    }

    public function render()
    {
        return view('livewire.flash-cards.create-flash-card');
    }
}
