<?php 

namespace App\Enums\FlashCards;

enum FlashCardStatuses: int {
    case READY_TO_LEARN = 1;
    case EXPECTS_REPEAT = 2;
    case STUDIED = 3;
}