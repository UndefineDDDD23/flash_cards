<div class="study-flash-cards-container">
    @foreach ($flashCards as $flashCard)
        <div class="flash-card">
            <h3>{{ $flashCard->user_meaning_text }}</h3>
            <p>{{ $flashCard->answer }}</p>
        </div>
    @endforeach
</div>
