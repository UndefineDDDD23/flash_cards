<?php 

namespace App\Services\Schedule\FlashCards;

use App\Enums\FlashCards\FlashCardStatuses;
use App\Models\FlashCards\FlashCard;
use Illuminate\Support\Facades\Log;

class UpdateFlashCardStatus
{
    public function __invoke(): void
    {
        $now = now()->timestamp;

        $flashCards = FlashCard::where('status_id', FlashCardStatuses::EXPECTS_REPEAT)
            ->with('currentRepetitionScheduleInterval')
            ->get();

        foreach ($flashCards as $flashCard) {
            if (!$flashCard->last_learned_at || !$flashCard->currentRepetitionScheduleInterval) {
                continue;
            }

            $lastLearned = $flashCard->last_learned_at->timestamp;
            $interval = $flashCard->currentRepetitionScheduleInterval->interval_seconds;

            $isTimeUp = ($lastLearned + $interval) <= $now;

            if($isTimeUp) {
                $flashCard->status = FlashCardStatuses::READY_TO_LEARN;
            }
        }
    }
}