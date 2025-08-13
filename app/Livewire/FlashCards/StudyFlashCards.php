<?php

namespace App\Livewire\FlashCards;

use App\Enums\FlashCards\FlashCardStatuses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;

/**
 * Livewire component for studying flash cards.
 * 
 * This component retrieves flash cards that are ready to learn and allows users to study them.
 * It listens for events related to flash card learning and forgetting to refresh the list of flash cards.
 */
class StudyFlashCards extends Component
{

    /**
     * The collection of flash cards that are ready to learn.
     *
     * @var Collection
     */
    public Collection $flashCards;

    /**
     * Key to force component refresh.
     *
     * @var int
     */
    public int $refreshKey = 0;

    public function mount()
    {
        $this->refreshFlashCards();
    }

    // Updates the list of cards on "learned" or "forgotten" events.
    // Loads cards with the READY_TO_LEARN status for the current user.
    // On error, clears the list and shows a message.
    #[On('flash-card-learned')]
    #[On('flash-card-forgot')]
    public function refreshFlashCards()
    {
        try {
            $user = Auth::user();
            $this->flashCards = $user->flashCards()
                ->whereHas('status', function ($statusQuery) {
                    $statusQuery->where('id', '=', FlashCardStatuses::READY_TO_LEARN->value);
                })->get();
                $this->refreshKey++;
        } catch (\Throwable $e) {
            $this->flashCards = new Collection();
            session()->flash('message', __('pages-content.flash_cards_get_error'));            
        }
    }

    public function render()
    {
        return view('livewire.flash-cards.study-flash-cards');
    }
}
