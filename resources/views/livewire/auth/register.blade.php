<main class="main">
    <div class="form-input-container">
        <h1>{{ __('pages-content.register') }}</h1>
        <div class="form-input-container__input">
            <x-validation.error-message :key="'username'"/>
            <label for="username">{{ __('pages-content.username') }}</label>
            <input type="text" id="username" wire:model="username" label="{{ __('pages-content.username') }}"/>        
        </div>        
        <div class="form-input-container__input">           
            <x-validation.error-message :key="'email'"/>
            <label for="email">{{ __('pages-content.email') }}</label>
            <input type="text" id="email" wire:model="email" label="{{ __('pages-content.email') }}"/>   
        </div>
        <div class="form-input-container__input">       
            <x-validation.error-message :key="'userNativeLanguageCode'"/>
            <label for="userNativeLanguageCode">{{ __('pages-content.native_language') }}</label>
            <select wire:model="userNativeLanguageCode" id="userNativeLanguageCode">
                <option value=""></option>
                @foreach ($languagesCollection as $language)
                    <option value="{{ $language->code }}">{{ $language->name }}</option>            
                @endforeach
            </select>
        </div>   
        <div class="form-input-container__input">   
            <x-validation.error-message :key="'userStudiedLanguageCode'"/>
            <label for="userStudiedLanguageCode">{{ __('pages-content.studied_language') }}</label>
            <select wire:model="userStudiedLanguageCode" id="userStudiedLanguageCode">
                <option value=""></option>
                @foreach ($languagesCollection as $language)
                    <option value="{{ $language->code }}">{{ $language->name }}</option>            
                @endforeach
            </select>
        </div> 
        <div class="form-input-container__input">        
            <x-validation.error-message :key="'password'"/>
            <label for="password">{{ __('pages-content.password') }}</label>   
            <input type="text" id="password" wire:model="password" label="{{ __('pages-content.password') }}"/>
        </div> 
        <div class="form-input-container__input">        
            <x-validation.error-message :key="'passwordConfirmation'"/>
            <label for="passwordConfirmation">{{ __('pages-content.password_confirmation') }}</label>
            <input type="text" id="passwordConfirmation" wire:model="passwordConfirmation" label="{{ __('pages-content.password_confirmation') }}"/>
        </div> 
        <button wire:click="register">{{ __('pages-content.send_button') }}</button>
    </div>
</main>