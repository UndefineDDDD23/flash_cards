<div class="flash-cards-list">
    @foreach ($flashCards as $flashCard)
        <div id="{{ $flashCard->id }}" class="flash-cards-list__item">
            <h3>{{ $flashCard->user_dictionary_element_text }}</h3>
            <p>{{ $flashCard->user_meaning_text }}</p>

            <x-flash-cards.translation-object-formated-markup :translation="$flashCard->translation"/>
            
            <button 
                data-flashcard-id="{{ $flashCard->id }}"
                data-text-open="{{ __('pages-content.close_ai_generated_dictionary_element_translation') }}"
                data-text-close="{{ __('pages-content.open_ai_generated_dictionary_element_translation') }}"
            >
                {{ __('pages-content.open_ai_generated_dictionary_element_translation') }}
            </button>
        </div>
    @endforeach
</div>
