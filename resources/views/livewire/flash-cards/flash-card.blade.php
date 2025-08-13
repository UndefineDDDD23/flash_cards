<div id="{{ $flashCard->id }}" class="flash-card">   
    @if($isEditing && !$isStudying)
        <div class="flash-card__editing-container">
            <x-validation.error-message :key="'editUserDictionaryElementText'"/>
            <label for="editUserDictionaryElementText">{{ __('pages-content.word') }}:</label>
            <input 
                type="text" 
                id="editUserDictionaryElementText"
                wire:model.defer="editUserDictionaryElementText" 
                placeholder="{{ __(key: 'pages-content.dictionary_element_text') }}" 
            />

            <x-validation.error-message :key="'editUserMeaningText'"/>
            <label for="editUserMeaningText">{{ __('pages-content.meaning') }}:</label>
            <textarea 
                id="editUserMeaningText"
                wire:model.defer="editUserMeaningText" 
                placeholder="{{ __('pages-content.meaning_text') }}"
            ></textarea>

            <label for="editFlashCardStatus">{{ __('pages-content.status') }}:</label>
            <select id="editFlashCardStatus" wire:model="editFlashCardStatus">
                @foreach (\App\Enums\FlashCards\FlashCardStatuses::cases() as $case)                        
                    <option value="{{ $case->value }}">
                        {{ __('pages-content.flash_card_statuses.' . $case->name) }}
                    </option>
                @endforeach
            </select>

            <button wire:click="saveEditing">{{ __('pages-content.save') }}</button>
            <button wire:click="cancelEditing">{{ __('pages-content.cancel') }}</button>
        </div>
    @else
        <div class="flash-card-front">
            <h3>{{ $flashCard->user_dictionary_element_text }}</h3>
            <button class="flip-btn">{{ __('pages-content.flip') }}</button>
        </div>

        <div class="flash-card-back">
            <p>{{ __('pages-content.meaning') }}: {{ $flashCard->user_meaning_text }}</p>
            <p>{{ __('pages-content.status') }}: {{ __('pages-content.flash_card_statuses.' . \App\Enums\FlashCards\FlashCardStatuses::tryFrom($flashCard->status->id)->name) }}</p>

            <x-flash-cards.translation-object-formated-markup :translation="$flashCard->translation"/>
            <div class="flash-card-back__buttons-container">     
            <button class="flip-btn">{{ __('pages-content.flip') }}</button>
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
                    <button wire:click="learned">{{ __('pages-content.learned') }}</button>
                    <button wire:click="forgot">{{ __('pages-content.forgot') }}</button>                
                @endif
            </div>
        </div>
    @endif
</div>