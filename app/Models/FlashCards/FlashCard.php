<?php

namespace App\Models\FlashCards;

use App\Models\User;
use App\Models\Dictionary\Translation;
use Illuminate\Database\Eloquent\Model;
use App\Models\FlashCards\FlashCardsStatus;
use App\Models\FlashCards\SpacedRepetitionScheduleInterval;

/**
 * User-specific flash card linked to a particular translation.
 *
 * Stores user-provided meaning and dictionary element text overrides, along
 * with the current spaced-repetition status.
 */
class FlashCard extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'status_id',
        'user_meaning_text',
        'user_dictionary_element_text',
        'current_repetition_schedule_interval_id',
        'last_learned_at',
        'translation_id',
    ];

    protected $casts = [
        'last_learned_at' => 'datetime',
    ];

    /**
     * Translation this card is based on.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function translation()
    {
        return $this->belongsTo(Translation::class);
    }

    /**
     * Owner of the flash card.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Current status of the flash card in the learning pipeline.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(FlashCardsStatus::class);
    }

    /**
     * Current spaced-repetition schedule interval for this flash card.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentRepetitionScheduleInterval()
    {
        return $this->belongsTo(SpacedRepetitionScheduleInterval::class, 'current_repetition_schedule_interval_id');    
    }
}
