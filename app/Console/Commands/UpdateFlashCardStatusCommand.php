<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Schedule\FlashCards\UpdateFlashCardStatus;

/**
 * Class UpdateFlashCardStatusCommand
 *
 * This command is responsible for updating the status of flash cards.
 */
class UpdateFlashCardStatusCommand extends Command {
    protected $signature = 'flashcards:update-status';

    /**
     * This command handles the update of flash card statuses.
     *
     * @var string
     */
    public function handle(UpdateFlashCardStatus $service): void {
        $service();
    }
}
