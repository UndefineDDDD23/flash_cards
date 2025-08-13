<div class="study-flash-cards-container">
    @foreach ($flashCards as $flashCard)
        <div class="flash-card">
            <x-messages.simple-flash-message :key="'message'"/>
            <livewire:flash-cards.flash-card
                :flashCard="$flashCard"
                :key="'flash-card-' . $flashCard->id . '-' . $refreshKey"
                :isStudying="true"
            />          
        </div>
    @endforeach
</div>
