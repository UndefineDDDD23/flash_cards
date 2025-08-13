<div id="{{ $flashCard->id }}" class="flash-card">
    @if($isEditing && !$isStudying)
        <x-validation.error-message :key="'editUserDictionaryElementText'"/>
        <input 
            type="text" 
            wire:model.defer="editUserDictionaryElementText" 
            placeholder="{{ __(key: 'pages-content.dictionary_element_text') }}" 
        />

        <x-validation.error-message :key="'editUserMeaningText'"/>
        <textarea 
            wire:model.defer="editUserMeaningText" 
            placeholder="{{ __('pages-content.meaning_text') }}"
        ></textarea>

        <select wire:model="editFlashCardStatus">
            @foreach (\App\Enums\FlashCards\FlashCardStatuses::cases() as $case)                        
                <option value="{{ $case->value }}">
                    {{ __('pages-content.flash_card_statuses.' . $case->name) }}
                </option>
            @endforeach
        </select>

        <button wire:click="saveEditing">{{ __('pages-content.save') }}</button>
        <button wire:click="cancelEditing">{{ __('pages-content.cancel') }}</button>
    @else
        <div class="flash-card-front">
            <h3>{{ $flashCard->user_dictionary_element_text }}</h3>
            <button class="flip-btn">Flip</button>
        </div>

        <div class="flash-card-back">
            <p>{{ $flashCard->user_meaning_text }}</p>
            <p>{{ __('pages-content.flash_card_statuses.' . \App\Enums\FlashCards\FlashCardStatuses::tryFrom($flashCard->status->id)->name) }}</p>

            <x-flash-cards.translation-object-formated-markup :translation="$flashCard->translation"/>

            @if(!$isStudying)            
                <button 
                    data-flashcard-id="{{ $flashCard->id }}"
                    data-text-open="{{ __('pages-content.close_ai_generated_dictionary_element_translation') }}"
                    data-text-close="{{ __('pages-content.open_ai_generated_dictionary_element_translation') }}"
                >
                    {{ __('pages-content.open_ai_generated_dictionary_element_translation') }}
                </button>
                <button wire:click="startEditing()">{{ __('pages-content.edit') }}</button>
                <button wire:click="deleteFlashCard()" onclick="return confirm('{{ __('pages-content.confirm_delete') }}')">{{ __('pages-content.delete') }}</button>

            @else
                <button class="flip-btn">Flip</button>
                <button wire:click="learned">{{ __('pages-content.learned') }}</button>
                <button wire:click="forgot">{{ __('pages-content.forgot') }}</button>                
            @endif
        </div>
    @endif
</div>