<?php

namespace App\Livewire\FlashCards;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

/**
 * Livewire component for displaying and managing the user's flashcards.
 * 
 * This component handles listing, editing, updating, and deleting flashcards.
 */
class FlashCardsList extends Component
{
    /**
     * Collection of flashcards belonging to the authenticated user.
     * 
     * @var Collection
     */
    public Collection $flashCards;

    /**
     * The ID of the flashcard currently being edited.
     * 
     * @var int|null
     */
    public ?int $editingFlashCardId = null;

    /**
     * Temporary field for storing the updated user meaning text during editing.
     * 
     * @var string|null
     */
    public ?string $editUserMeaningText = null;

    /**
     * Temporary field for storing the updated flashcard status during editing.
     * 
     * @var int|null
     */
    public ?int $editFlashCardStatus = null;

    /**
     * Temporary field for storing the updated user dictionary element text during editing.
     * 
     * @var string|null
     */
    public ?string $editUserDictionaryElementText = null;

    /**
     * Initializes the component by loading all flashcards for the authenticated user.
     * 
     * @return void
     */
    public function mount() {
        // Load all user's flashcards with their related translations
        $this->flashCards = auth()->user()->flashCards()->with('translation')->get();
    }

    /**
     * Starts the editing process for the selected flashcard.
     * 
     * @param int $flashCardId The ID of the flashcard to edit.
     * 
     * @return void
     */
    public function startEditing(int $flashCardId){
        // Find the flashcard in the loaded collection
        $flashCard = $this->flashCards->firstWhere('id', $flashCardId);
        if (!$flashCard) {
            session()->flash('error', 'Flash card not found.');
            return;
        }

        // Store flashcard details into temporary editing fields
        $this->editingFlashCardId = $flashCardId;
        $this->editUserMeaningText = $flashCard->user_meaning_text;
        $this->editUserDictionaryElementText = $flashCard->user_dictionary_element_text;        
        $this->editFlashCardStatus = $flashCard->status->id;
    }

    /**
     * Cancels the editing process and clears all temporary editing fields.
     * 
     * @return void
     */
    public function cancelEditing() {
        $this->editingFlashCardId = null;
        $this->editUserMeaningText = null;
        $this->editUserDictionaryElementText = null;
        $this->editFlashCardStatus = null;
    }

    /**
     * Saves the changes made to a flashcard after editing.
     * 
     * @return void
     */
    public function saveEditing() {
        $this->validate([
            'editUserMeaningText'               => 'required|string|max:1000',
            'editUserDictionaryElementText'     => 'required|string|max:255',
            'editingFlashCardId'                => 'required|exists:flash_cards,id',
            'editFlashCardStatus'               => 'required|integer',
        ]);

        // Find the flashcard belonging to the authenticated user
        $flashCard = auth()->user()->flashCards()->find($this->editingFlashCardId);

        if (!$flashCard) {
            session()->flash('error', 'Flash card not found.');
            $this->cancelEditing();
            return;
        }

        // Update the flashcard in the database
        $flashCard->update([
            'user_meaning_text' => $this->editUserMeaningText,
            'user_dictionary_element_text' => $this->editUserDictionaryElementText,
            'status_id' => $this->editFlashCardStatus,
        ]);

        // Update the flashcard in the local collection to reflect changes without reloading from DB
        $this->flashCards = $this->flashCards->map(function ($card) use ($flashCard) {
            if ($card->id === $flashCard->id) {
                $card->user_meaning_text = $flashCard->user_meaning_text;
                $card->user_dictionary_element_text = $flashCard->user_dictionary_element_text;
            }
            return $card;
        });

        $this->cancelEditing();
    }

    /**
     * Deletes a flashcard belonging to the authenticated user.
     * 
     * @param int $flashCardId The ID of the flashcard to delete.
     * 
     * @return void
     */
    public function deleteFlashCard(int $flashCardId) {
        // Find the flashcard in the database
        $flashCard = auth()->user()->flashCards()->find($flashCardId);
        if ($flashCard) {
            // Delete from database
            $flashCard->delete();

            // Remove from the local collection
            $this->flashCards = $this->flashCards->reject(fn($card) => $card->id === $flashCardId);

            session()->flash('message', 'Flash card deleted successfully.');
        } else {
            session()->flash('error', 'Flash card not found.');
        }
    }

    /**
     * Renders the Livewire view for the flashcards list.
     * 
     * @return \Illuminate\View\View
     */
    public function render(){
        return view('livewire.flash-cards.flash-cards-list');
    }
}
