<div class="flash-cards-list">
    <x-messages.simple-flash-message :key="'search-message'" />
    <x-validation.error-message :key="'searchElementText'"/>
    <div class="search">
        <input wire:model.live.debounce.250ms="searchElementText" type="text" />
        <button wire:click="search">{{ __('pages-content.search') }}</button>
    </div>
    <div class="flash-cards-container">
        @foreach ($flashCards as $flashCard)
            <livewire:flash-cards.flash-card :flashCard="$flashCard" :key="'flash-card-'.$flashCard->id" :isStudying="false"/>
        @endforeach
    </div>
</div>
