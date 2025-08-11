<?php

namespace App\Livewire\FlashCards;

use App\Models\User;
use App\Services\AI\OpenRouterMistral;
use Livewire\Component;
use App\Models\Languages\Language;
use Illuminate\Support\Facades\Auth;
use App\Models\Dictionary\Translation;
use App\Contracts\AI\OpenRouterInterface;
use App\Models\Dictionary\DictionaryElement;
use App\Services\AI\OpenRouterDictionary;
use App\Contracts\AI\OpenRouterDictionaryInterface;
use App\Services\Dictionary\WordTranslationService;
use App\Contracts\Dictionary\WordTranslationServiceInterface;

class CreateFlashCard extends Component
{
    public string $studiedLanguageWord;
    public string $translatedStudiedLanguageWord;
    public string $userWrittenMeaning;

    public Translation $translation;
    
    public function mount() {
    }

    private function generateDescriptionByAI(User $user, WordTranslationServiceInterface $wordTranslationService, OpenRouterDictionaryInterface $openRouterDictionary) {
        $dictionaryElement = DictionaryElement::where('element_text', '=', $this->studiedLanguageWord)->first();
        if(!$dictionaryElement) {            
            $dictionaryElement = $openRouterDictionary->generateWordDescription($user->nativeLanguage, $user->studiedLanguage, $this->studiedLanguageWord);
            if(!$dictionaryElement) {
                session()->flash('generatedDescriptionByAI', 'AI could not generate a description for the word.');
                return;
            }
        }
        $this->translation = $dictionaryElement->translations()->firstWhere('translation_language_id', $user->nativeLanguage->id);
    }

    public function createFlashCard() {
        $this->validate([
            'studiedLanguageWord' => 'required|string|max:255',
            'userWrittenMeaning' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $wordTranslationService = new WordTranslationService();
        $openRouterModel = new OpenRouterMistral();
        $openRouterDictionary = new OpenRouterDictionary($wordTranslationService, $openRouterModel);

        $this->generateDescriptionByAI($user, $wordTranslationService, $openRouterDictionary);
    }

    public function render()
    {
        return view('livewire.flash-cards.create-flash-card');
    }
}
