<div class="flash-cards-list">
    @foreach ($flashCards as $flashCard)
        <div id="{{ $flashCard->id }}" class="flash-cards-list__item">
            @if($editingFlashCardId === $flashCard->id)
                <x-validation.error-message :key="'editUserDictionaryElementText'"/>
                <input 
                    type="text" 
                    wire:model.defer="editUserDictionaryElementText" 
                    placeholder="{{ __('pages-content.dictionary_element_text') }}" 
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

                <button wire:click="startEditing({{ $flashCard->id }})">{{ __('pages-content.edit') }}</button>
                <button wire:click="deleteFlashCard({{ $flashCard->id }})" onclick="return confirm('{{ __('pages-content.confirm_delete') }}')">{{ __('pages-content.delete') }}</button>
            @endif
        </div>
    @endforeach
</div>
