<div class="create-flash-card">
    <x-validation.error-message :key="'studiedLanguageWord'"/>
    <label for="studiedLanguageWord">{{ __('pages-content.native_language') }}</label>
    <input wire:model="studiedLanguageWord" id="studiedLanguageWord" type="text" class="create-flash-card__input"/>
    
    <x-validation.error-message :key="'studiedLanguageWord'"/>
    <label for="translatedStudiedLanguageWord">{{ __('pages-content.studied_language') }}</label>
    <input wire:model="translatedStudiedLanguageWord" id="translatedStudiedLanguageWord" type="text" class="create-flash-card__input"/>
    
    <x-validation.error-message :key="'studiedWordDescription'"/>
    <label for="studiedWordDescription">{{ __('pages-content.native_language_word_description') }}</label>
    <textarea wire:model="studiedWordDescription" id="studiedWordDescription"></textarea>

    <button wire:click="generateDescriptionByAI" style="display:flex;align-items:center;justify-content:center;">
        <span wire:loading.remove wire:target="generateDescriptionByAI">
            {{ __('pages-content.generate_ai_description_button') }}
        </span>
        <div wire:loading wire:target="generateDescriptionByAI" class="loading-spinner"></div>
    </button>
</div>
