<?php

namespace App\Livewire\FlashCards;

use App\Models\Dictionary\DictionaryElement;
use App\Services\Dictionary\DictionaryService;
use Livewire\Component;
use App\Models\Languages\Language;
use Illuminate\Support\Facades\Auth;
use App\Services\AI\OpenRouterDictionaryMistral;

class CreateFlashCard extends Component
{
    public string $studiedLanguageWord;
    public string $translatedStudiedLanguageWord;
    public string $studiedWordDescription;
    
    public function mount() {
    }

    public function generateDescriptionByAI() {
        $dictionaryService = new DictionaryService();
        $user = Auth::user();
        $dictionaryElement = DictionaryElement::where('element_text', '=', $this->studiedLanguageWord)->first();
        if(!$dictionaryElement) {            
            $openRouterModel = new OpenRouterDictionaryMistral($dictionaryService);
            $dictionaryElement = $openRouterModel->generateWordDescription($user->nativeLanguage, $user->studiedLanguage, $this->studiedLanguageWord);
        }
        $translation = $dictionaryElement->translations()->firstWhere('translation_language_id', $user->nativeLanguage->id);
        $this->translatedStudiedLanguageWord = $translation->translated_element_text;
        $this->studiedWordDescription = $dictionaryService->getCompiledDictionaryElementDescription($dictionaryElement, $user->nativeLanguage);
    }

    public function create() {
        
    }

    public function render()
    {
        return view('livewire.flash-cards.create-flash-card');
    }
}
