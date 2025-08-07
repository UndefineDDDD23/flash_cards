@isset($translation)
    <div>
        <h3>{{ __('pages-content.studied_language') }}</h3>
        <pre>{{  __('pages-content.meaning') }}: </br>{{ $translation->translated_meaning }}</pre>
        <pre>{{  __('pages-content.how_to_use') }}: </br>{{ $translation->translated_how_to_use }}</pre>           
        <pre>{{  __('pages-content.synonyms') }}: </br>{{ $translation->formatted_synonyms }}</pre>    
        <pre>{{  __('pages-content.examples') }}: </br>{{ $translation->formatted_examples }}</pre>    
    </div> 
@endisset