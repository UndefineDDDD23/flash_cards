<?php

namespace App\Livewire\FlashCards;

use App\Enums\FlashCards\FlashCardStatuses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class StudyFlashCards extends Component
{

    public Collection $flashCards;

    public function mount()
    {
        try {
            $user = Auth::user();
            $this->flashCards = $user->flashCards()
                ->whereHas('status', function ($statusQuery) {
                    $statusQuery->where('id', '=', FlashCardStatuses::READY_TO_LEARN->value);
                })->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->flashCards = new Collection();
            session()->flash('error', __('No flashcards found for the user.'));
        } catch (\Throwable $e) {
            $this->flashCards = new Collection();
            session()->flash('error', __('An error occurred while fetching flashcards.'));
            
        }
    }

    public function render()
    {
        return view('livewire.flash-cards.study-flash-cards');
    }
}
