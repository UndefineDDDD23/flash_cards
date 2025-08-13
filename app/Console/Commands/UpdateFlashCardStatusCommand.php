<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Schedule\FlashCards\UpdateFlashCardStatus;

class UpdateFlashCardStatusCommand extends Command
{
    protected $signature = 'flashcards:update-status';

    public function handle(UpdateFlashCardStatus $service): void
    {
        $service();
    }
}
