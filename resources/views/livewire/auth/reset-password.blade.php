<main class="main">
    <div class="form-input-container">
        <h1>{{ __('pages-content.form-input') }}</h1>
        <div class="form-input-container__input">  
            <x-validation.error-message :key="'password'"/>
            <label for="password">{{ __('pages-content.password') }}</label>   
            <input id="password" wire:model="password" label="{{ __('pages-content.password') }}"/>
        </div> 
        <div class="form-input-container__input">     
            <x-validation.error-message :key="'passwordConfirmation'"/>
            <label for="passwordConfirmation">{{ __('pages-content.password_confirmation') }}</label>
            <input id="passwordConfirmation" wire:model="passwordConfirmation" label="{{ __('pages-content.password_confirmation') }}"/>
        </div> 
        @if (session()->has('reset-password-link-sent'))
            <p>Reset password link didnt send</p>
        @endif
        <button wire:click="resetPassword">{{ __('pages-content.send_button') }}</button>
    </div>
</main>