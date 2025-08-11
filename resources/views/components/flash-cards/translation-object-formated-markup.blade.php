@props(['translation'])

{{-- This component displays the formatted translation details for a flash card. --}}
{{-- It is used to show the meaning, usage, synonyms, and examples of a word in the studied language. --}}
@isset($translation)
    <div class="generated-dictionary-element-translation">
        <h3>{{ __('pages-content.studied_language') }}</h3>
        <pre>{{  __('pages-content.meaning') }}: </br>{{ $translation->translated_meaning }}</pre>
        <pre>{{  __('pages-content.how_to_use') }}: </br>{{ $translation->translated_how_to_use }}</pre>           
        <pre>{{  __('pages-content.synonyms') }}: </br>{{ $translation->formatted_synonyms }}</pre>    
        <pre>{{  __('pages-content.examples') }}: </br>{{ $translation->formatted_examples }}</pre>    
    </div> 
@endisset