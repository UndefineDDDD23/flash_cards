<?php

namespace App\Livewire\FlashCards;

use App\Enums\FlashCards\FlashCardStatuses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;

class StudyFlashCards extends Component
{

    public Collection $flashCards;

    public function mount()
    {
        $this->refreshFlashCards();
    }

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
