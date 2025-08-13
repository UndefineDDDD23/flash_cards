<?php 

namespace App\Enums\FlashCards;

/**
 * Enum representing the statuses of flash cards.
 * 
 * Value it is id in database
 * 
 * @package App\Enums\FlashCards
 */
enum FlashCardStatuses: int {
    case READY_TO_LEARN = 1;
    case EXPECTS_REPEAT = 2;
    case STUDIED = 3;
}