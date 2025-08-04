<main class="main">
    <div class="form-input-container">
        <h1>{{ __('pages-content.form-input') }}</h1>
        <div class="form-input-container__input">        
            @error('password')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="password">{{ __('pages-content.password') }}</label>   
            <input id="password" wire:model="password" label="{{ __('pages-content.password') }}"/>
        </div> 
        <div class="form-input-container__input">        
            @error('passwordConfirmation')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="passwordConfirmation">{{ __('pages-content.password_confirmation') }}</label>
            <input id="passwordConfirmation" wire:model="passwordConfirmation" label="{{ __('pages-content.password_confirmation') }}"/>
        </div> 
        @if (session()->has('reset-password-link-sent'))
            <p>Reset password link didnt send</p>
        @endif
        <button wire:click="resetPassword">{{ __('pages-content.send_button') }}</button>
    </div>
</main>