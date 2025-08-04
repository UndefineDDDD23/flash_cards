<main class="main">
    <div class="register-container">
        <h1>{{ __('pages-content.login') }}</h1>      
        <div class="register-container__input">            
            @error('email')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="email">{{ __('pages-content.email') }}</label>
            <input id="email" wire:model="email" label="{{ __('pages-content.email') }}"/>   
        </div>
        <div class="register-container__input">        
            @error('password')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="password">{{ __('pages-content.password') }}</label>   
            <input id="password" wire:model="password" label="{{ __('pages-content.password') }}"/>
        </div> 
        
        <div class="register-container__input">        
            <label for="remember">{{ __('pages-content.remember') }}</label>   
            <input id="remember" type="checkbox" wire:model="remember" label="{{ __('pages-content.password') }}"/>
        </div> 
        <button wire:click="login">{{ __('pages-content.send_button') }}</button>
    </div>
</main>