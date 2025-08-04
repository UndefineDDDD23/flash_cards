<main class="main">
    <div class="form-input-container">
        <h1>{{ __('pages-content.login') }}</h1>      
        <div class="form-input-container__input">            
            @error('email')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="email">{{ __('pages-content.email') }}</label>
            <input id="email" wire:model="email" label="{{ __('pages-content.email') }}"/>   
        </div>
        @if(session()->has('sent-reset-password-link'))
            <p>Reset password link sent</p>
        @endif
        <button wire:click="sendResetPasswordLink">{{ __('pages-content.send_button') }}</button>
    </div>
</main>