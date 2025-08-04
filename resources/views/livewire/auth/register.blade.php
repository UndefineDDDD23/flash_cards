<main class="main">
    <div class="register-container">
        <h1>{{ __('pages-content.register') }}</h1>
        <div class="register-container__input">
            @error('username')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="username">{{ __('pages-content.username') }}</label>
            <input id="username" wire:model="username" label="{{ __('pages-content.username') }}"/>        
        </div>        
        <div class="register-container__input">            
            @error('email')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="email">{{ __('pages-content.email') }}</label>
            <input id="email" wire:model="email" label="{{ __('pages-content.email') }}"/>   
        </div>
        <div class="register-container__input">        
            @error('userNativeLanguageCode')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="userNativeLanguageCode">{{ __('pages-content.native_language') }}</label>
            <select wire:model="userNativeLanguageCode" id="userNativeLanguageCode">
                <option value=""></option>
                @foreach ($languagesCollection as $language)
                    <option value="{{ $language->code }}">{{ $language->name }}</option>            
                @endforeach
            </select>
        </div>   
        <div class="register-container__input">        
            @error('userStudiedLanguageCode')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="userStudiedLanguageCode">{{ __('pages-content.studied_language') }}</label>
            <select wire:model="userStudiedLanguageCode" id="userStudiedLanguageCode">
                <option value=""></option>
                @foreach ($languagesCollection as $language)
                    <option value="{{ $language->code }}">{{ $language->name }}</option>            
                @endforeach
            </select>
        </div> 
        <div class="register-container__input">        
            @error('password')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="password">{{ __('pages-content.password') }}</label>   
            <input id="password" wire:model="password" label="{{ __('pages-content.password') }}"/>
        </div> 
        <div class="register-container__input">        
            @error('passwordConfirmation')
               <p class="validation-error">{{ $message }}</p> 
            @enderror
            <label for="passwordConfirmation">{{ __('pages-content.password_confirmation') }}</label>
            <input id="passwordConfirmation" wire:model="passwordConfirmation" label="{{ __('pages-content.password_confirmation') }}"/>
        </div> 
        <button wire:click="register">{{ __('pages-content.send_button') }}</button>
    </div>
</main>