<?php 

namespace App\Services\Schedule\FlashCards;

use App\Enums\FlashCards\FlashCardStatuses;
use App\Models\FlashCards\FlashCard;
use Illuminate\Support\Facades\Log;

/**
 * Class UpdateFlashCardStatus
 * 
 * This service updates the status of flash cards based on their last learned time and current repetition schedule.
 * It checks if the time for the next repetition has come and updates the status accordingly.
 */
class UpdateFlashCardStatus
{
    /**
     * Invokes the service to update flash card statuses.
     *
     * @return void
     */
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