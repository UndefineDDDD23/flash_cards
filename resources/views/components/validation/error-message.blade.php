@props(['key'])
{{--
  Component: Inline validation error message
  Renders the first validation error for the provided field key.
--}}
@error($key)
    <p class="validation-error">{{ $message }}</p> 
@enderror