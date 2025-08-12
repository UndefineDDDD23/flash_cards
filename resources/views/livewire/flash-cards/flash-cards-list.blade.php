<div class="flash-cards-list">
    <x-messages.simple-flash-message key="search-message" />
    <x-validation.error-message :key="'searchElementText'"/>
    <input wire:model.live.debounce.250ms="searchElementText" type="text" />
    <button wire:click="search">{{ __('pages-content.search') }}</button>
    @foreach ($flashCards as $flashCard)
        <livewire:flash-cards.flash-card :flashCard="$flashCard" :key="'flash-card-'.$flashCard->id"/>
    @endforeach
</div>
