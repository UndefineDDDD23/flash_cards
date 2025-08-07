<div class="create-flash-card">
    <x-validation.error-message :key="'studiedLanguageWord'"/>
    <label for="studiedLanguageWord">{{ __('pages-content.native_language') }}</label>
    <input wire:model="studiedLanguageWord" id="studiedLanguageWord" type="text" class="create-flash-card__input"/>
    
    <button wire:click="generateDescriptionByAI" style="display:flex;align-items:center;justify-content:center;">
        <span wire:loading.remove wire:target="generateDescriptionByAI">
            {{ __('pages-content.generate_ai_description_button') }}
        </span>
        <div wire:loading wire:target="generateDescriptionByAI" class="loading-spinner"></div>
    </button>

    
    @isset($translation)
        <h3>{{ __('pages-content.studied_language') }}</h3>
        <pre>{{  __('pages-content.meaning') }}: </br>{{ $translation->translated_meaning }}</pre>
        <pre>{{  __('pages-content.how_to_use') }}: </br>{{ $translation->translated_how_to_use }}</pre>           
        <pre>{{  __('pages-content.synonyms') }}: </br>{{ $translation->formatted_synonyms }}</pre>    
        <pre>{{  __('pages-content.examples') }}: </br>{{ $translation->formatted_examples }}</pre>     
    @endisset
</div>
