<div class="create-flash-card">
    <div class="create-flash-card__element">
        <x-validation.error-message :key="'studiedLanguageWord'"/>
        <label for="studiedLanguageWord">{{ __('pages-content.native_language') }}</label>
        <input wire:model="studiedLanguageWord" id="studiedLanguageWord" type="text" class="create-flash-card__input"/>
    </div>

    <div class="create-flash-card__element">
        <x-validation.error-message :key="'userWrittenMeaning'"/>
        <label for="userWrittenMeaning">{{ __('pages-content.user_written_meaning') }}</label>
        <textarea wire:model="userWrittenMeaning" id="userWrittenMeaning" type="text" class="create-flash-card__input"> </textarea>
    </div>
    
    <button wire:click="createFlashCard" style="display:flex;align-items:center;justify-content:center;">
        <span wire:loading.remove wire:target="createFlashCard">
            {{ __('pages-content.create_flash_card') }}
        </span>
        <div wire:loading wire:target="createFlashCard" class="loading-spinner"></div>
    </button>
    
    <x-flash-cards.translation-object-formated-markup :translation="$translation"/>
</div>
