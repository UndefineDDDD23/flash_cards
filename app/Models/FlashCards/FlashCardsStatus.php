<?php

namespace App\Models\FlashCards;

use App\Models\FlashCards\FlashCard;
use Illuminate\Database\Eloquent\Model;

/**
 * Catalog of available statuses for flash cards (e.g., new, learning, review).
 */
class FlashCardsStatus extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description'];

    /**
     * Flash cards currently marked with this status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function flashCards()
    {
        return $this->hasMany(FlashCard::class, 'status_id');
    }
}
