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

/**
 * Livewire component responsible for creating a new flashcard.
 * 
 * This component allows the user to input a word in their studied language,
 * provide their own meaning for the word, and optionally enrich the flashcard
 * with AI-generated descriptions and translations.
 */
class CreateFlashCard extends Component {
    /**
     * The word being studied by the user in their target language.
     * 
     * @var string
     */
    public string $studiedLanguageWord;

    /**
     * The meaning or definition of the studied word as written by the user.
     * 
     * @var string
     */
    public string $userWrittenMeaning;

    /**
     * The translation object associated with the studied word.
     * 
     * @var Translation
     */
    public Translation $translation;

    /**
     * Generates a description of the studied word using AI, if available.
     * 
     * If the word does not exist in the dictionary, it will attempt to 
     * generate its description and translation using the AI-powered 
     * dictionary service. If the description generation fails, returns false.
     * 
     * @param User $user The authenticated user.
     * @param WordTranslationServiceInterface $wordTranslationService The service for handling word translations.
     * @param OpenRouterDictionaryInterface $openRouterDictionary The AI-powered dictionary service.
     * 
     * @return bool Returns true if the description was successfully generated or found, false otherwise.
     */
    private function generateDescriptionByAI(
        User $user, 
        WordTranslationServiceInterface $wordTranslationService, 
        OpenRouterDictionaryInterface $openRouterDictionary
    ): bool {
        // Attempt to find the dictionary element for the studied word
        $dictionaryElement = DictionaryElement::where('element_text', '=', $this->studiedLanguageWord)->first();

        // If not found, try generating it using the AI-powered dictionary
        if(!$dictionaryElement) {            
            $dictionaryElement = $openRouterDictionary->generateWordDescription(
                $user->nativeLanguage, 
                $user->studiedLanguage, 
                $this->studiedLanguageWord
            );

            // If AI generation failed, return false
            if(!$dictionaryElement) {
                return false;
            }
        }

        // Get the translation for the user's native language
        $this->translation = $dictionaryElement
            ->translations()
            ->firstWhere('translation_language_id', $user->nativeLanguage->id);

        return true;
    }

    /**
     * Handles the creation of a new flashcard.
     * 
     * Validates the input fields, generates a translation/description if possible,
     * and saves the new flashcard to the database.
     * Redirects the user to the flashcards panel upon successful creation.
     * 
     * @return void
     */
    public function createFlashCard() {
        $this->validate([
            'studiedLanguageWord'   => 'required|string|min:2|max:255',
            'userWrittenMeaning'    => 'required|string|min:2|max:1000',
        ]);   

        // Remove unnecessary spaces from the studied word
        $this->studiedLanguageWord = trim($this->studiedLanguageWord);      
        
        // Get the currently authenticated user
        $user = Auth::user();

        // Initialize required services
        $wordTranslationService = new WordTranslationService();
        $openRouterModel = new OpenRouterMistral();
        $openRouterDictionary = new OpenRouterDictionary($wordTranslationService, $openRouterModel);

        // Attempt to generate AI-based description and translation
        $generatedAiDesriptionStatus = $this->generateDescriptionByAI(
            $user, 
            $wordTranslationService, 
            $openRouterDictionary
        );

        // Create a new flashcard entry in the database
        FlashCard::create([
            'user_id'                       => $user->id,
            'status_id'                     => FlashCardStatuses::READY_TO_LEARN->value,
            'user_meaning_text'             => $this->userWrittenMeaning,
            'user_dictionary_element_text'  => $this->studiedLanguageWord,
            'translation_id'                => $generatedAiDesriptionStatus ? $this->translation->id : null,
        ]);

        $this->redirect(route('flash-cards.panel'));
    }

    /**
     * Renders the Livewire view for creating a flashcard.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.flash-cards.create-flash-card');
    }
}
