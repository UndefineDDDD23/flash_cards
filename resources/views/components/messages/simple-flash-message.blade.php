@props(['key'])

@if(session()->has($key))
    <div class="flash-message">
        <p>{{ session()->get($key) }}</p>
    </div>    
@endif