<main class="main">
    <div class="form-input-container">
        <h1>{{ __('pages-content.login') }}</h1>      
        <div class="form-input-container__input">
            <x-validation.error-message :key="'email'"/>
            <label for="email">{{ __('pages-content.email') }}</label>
            <input id="email" wire:model="email" label="{{ __('pages-content.email') }}"/>   
        </div>
        <div class="form-input-container__input">
            <x-validation.error-message :key="'password'"/>
            <label for="password">{{ __('pages-content.password') }}</label>   
            <input id="password" wire:model="password" label="{{ __('pages-content.password') }}"/>
        </div> 
        
        <div class="form-input-container__input">        
            <label for="remember">{{ __('pages-content.remember') }}</label>   
            <input id="remember" type="checkbox" wire:model="remember" label="{{ __('pages-content.password') }}"/>
        </div> 

        <a href="{{ route('password.request') }}">Forgot password</a>

        <button wire:click="login">{{ __('pages-content.send_button') }}</button>
    </div>
</main>