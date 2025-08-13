<main class="main">
    <div class="form-input-container">
        <h1>{{ __('pages-content.login') }}</h1>      
        <div class="form-input-container__input">   
            <x-validation.error-message :key="'email'"/>
            <label for="email">{{ __('pages-content.email') }}</label>
            <input type="text" id="email" wire:model="email" label="{{ __('pages-content.email') }}"/>   
        </div>
        @if(session()->has('sent-reset-password-link'))
            <p>{{ __('pages-content.reset_password_link_sent') }}</p>
        @endif
        <button wire:click="sendResetPasswordLink">{{ __('pages-content.send_button') }}</button>
    </div>
</main>