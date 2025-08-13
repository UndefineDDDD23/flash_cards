<?php

namespace App\Livewire\FlashCards;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\Attributes\On; 

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
     * Search term for filtering flashcards.
     * 
     * @var string
     */
    public string $searchElementText = '';

    /**
     * Initializes the component by loading all flashcards for the authenticated user.
     * 
     * @return void
     */
    public function mount() {
        $this->refreshFlashCards();
    }

    #[On('flash-card-edited')]
    #[On('flash-card-deleted')]
    #[On('flash-card-created')]
    public function refreshFlashCards() {
        $this->flashCards = auth()->user()->flashCards()->with('translation')->get();
    }
   
    /**
     * Searches for flashcards based on the property .
     * 
     * @return void
     */
    public function search() {
        $this->validate([
            'searchElementText' => 'string|max:255',
        ]);

        if($this->searchElementText === '') {
            // If search term is empty, reload all flashcards
            $this->refreshFlashCards();
            return;

        }

        // Filter flashcards based on the search term
        $this->flashCards = auth()->user()->flashCards()
            ->where('user_meaning_text', 'like', '%' . $this->searchElementText . '%')
            ->orWhere('user_dictionary_element_text', 'like', '%' . $this->searchElementText . '%')
            ->with('translation')
            ->get();
        if ($this->flashCards->isEmpty()) {
            session()->flash('search-message', __('pages-content.search-message'));
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
