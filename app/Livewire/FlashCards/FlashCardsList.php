<?php

namespace App\Livewire\FlashCards;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class FlashCardsList extends Component
{
    public Collection $flashCards;

    public function mount()
    {
        $this->flashCards = auth()->user()->flashCards()->with('translation')->get();
    }
    
    public function render()
    {
        return view('livewire.flash-cards.flash-cards-list');
    }
}
