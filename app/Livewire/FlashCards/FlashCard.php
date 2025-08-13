<?php

namespace App\Livewire\FlashCards;

use App\Enums\FlashCards\FlashCardStatuses;
use App\Models\FlashCards\SpacedRepetitionScheduleInterval;
use Livewire\Component;
use App\Models\FlashCards\FlashCard as FlashCardModel;
use App\Livewire\FlashCards\FlashCardsList;

class FlashCard extends Component
{   
    public FlashCardModel $flashCard;

    /**
     * Indicates whether the flashcard is currently being studied.
     * 
     * @var bool
     */
    public bool $isStudying = false;

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
     * Indicates whether the flashcard is currently being edited.
     * 
     * @var bool
     */
    public bool $isEditing = false;

    /**
     * Starts the editing process for the selected flashcard.
     *      
     * @return void
     */
    public function startEditing(){
        // Store flashcard details into temporary editing fields
        $this->isEditing = true;
        $this->editUserMeaningText = $this->flashCard->user_meaning_text;
        $this->editUserDictionaryElementText = $this->flashCard->user_dictionary_element_text;        
        $this->editFlashCardStatus = $this->flashCard->status->id;
    }

    /**
     * Cancels the editing process and clears all temporary editing fields.
     * 
     * @return void
     */
    public function cancelEditing() {
        $this->isEditing = false;
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
            'editFlashCardStatus'               => 'required|integer',
        ]);
        
        // Update the flashcard in the database
        $this->flashCard->update([
            'user_meaning_text' => $this->editUserMeaningText,
            'user_dictionary_element_text' => $this->editUserDictionaryElementText,
            'status_id' => $this->editFlashCardStatus,
        ]);

        $this->dispatch('flash-card-edited')->to(FlashCardsList::class); 

        $this->cancelEditing();
    }

    /**
     * Deletes a flashcard belonging to the authenticated user.
     * 
     * @param int $flashCardId The ID of the flashcard to delete.
     * 
     * @return void
     */
    public function deleteFlashCard() {
            // Delete from database
            $this->flashCard->delete();
            $this->dispatch('flash-card-deleted')->to(FlashCardsList::class);
    }

    /**
     * Marks the flashcard as learned, updating its last learned time and incrementing the spaced repetition interval.
     * 
     * @return void
     */
    public function learned() {        
        $this->flashCard->last_learned_at = now();
        if($this->flashCard->current_repetition_schedule_interval_id == SpacedRepetitionScheduleInterval::all()->max('id')) {
            $this->flashCard->status_id = FlashCardStatuses::STUDIED;
        }
        else {
            $this->flashCard->status_id = FlashCardStatuses::EXPECTS_REPEAT;
        }
        if($this->flashCard->currentRepetitionScheduleInterval) {
            $result = $this->flashCard->current_repetition_schedule_interval_id + $this->flashCard->currentRepetitionScheduleInterval->learning_step_forward;
            if($result >= 1) {
                $this->flashCard->current_repetition_schedule_interval_id = $result; // Decrement the interval by the learning step forward
            } else {
                $this->flashCard->current_repetition_schedule_interval_id = 1; // Default to 1 if no interval is set
            }
        } else {
            $this->flashCard->current_repetition_schedule_interval_id = 1; // Default to 1 if no interval is set
        }        
        
        $this->flashCard->save();
        $this->dispatch('flash-card-learned')->to(StudyFlashCards::class);
    }

    public function forgot() {        
        if($this->flashCard->currentRepetitionScheduleInterval) {
            $result = $this->flashCard->current_repetition_schedule_interval_id - $this->flashCard->currentRepetitionScheduleInterval->learning_step_back;
            if($result >= 1) {
                $this->flashCard->current_repetition_schedule_interval_id = $result; // Decrement the interval by the learning step forward
            } else {
                $this->flashCard->current_repetition_schedule_interval_id = 1; // Default to 1 if no interval is set
            }
        } else {
            $this->flashCard->current_repetition_schedule_interval_id = 1; // Default to 1 if no interval is set
        }
        $this->flashCard->status_id = FlashCardStatuses::READY_TO_LEARN;
        $this->flashCard->save();
        $this->dispatch('flash-card-forgot')->to(StudyFlashCards::class);
    }

    public function render()
    {
        return view('livewire.flash-cards.flash-card');
    }
}
