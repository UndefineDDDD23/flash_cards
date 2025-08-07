<?php

namespace App\Livewire\FlashCards;

use App\Models\Dictionary\DictionaryElement;
use App\Services\Dictionary\WordTranslationService;
use Livewire\Component;
use App\Models\Languages\Language;
use App\Models\Dictionary\Translation;
use Illuminate\Support\Facades\Auth;
use App\Services\AI\OpenRouterDictionaryMistral;

class CreateFlashCard extends Component
{
    public string $studiedLanguageWord;
    public string $translatedStudiedLanguageWord;
    public string $studiedWordDescription;

    public Translation $translation;
    
    public function mount() {
    }

    public function generateDescriptionByAI() {
        $wordTranslationService = new WordTranslationService();
        $user = Auth::user();
        $dictionaryElement = DictionaryElement::where('element_text', '=', $this->studiedLanguageWord)->first();
        if(!$dictionaryElement) {            
            $openRouterModel = new OpenRouterDictionaryMistral($wordTranslationService);
            $dictionaryElement = $openRouterModel->generateWordDescription($user->nativeLanguage, $user->studiedLanguage, $this->studiedLanguageWord);
        }
        $this->translation = $dictionaryElement->translations()->firstWhere('translation_language_id', $user->nativeLanguage->id);
    }

    public function create() {
        
    }

    public function render()
    {
        return view('livewire.flash-cards.create-flash-card');
    }
}
