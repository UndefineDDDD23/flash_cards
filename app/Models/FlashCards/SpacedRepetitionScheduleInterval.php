<?php

namespace App\Models\FlashCards;

use Illuminate\Database\Eloquent\Model;

/**
 * Defines SRS (Spaced Repetition System) intervals and learning step changes.
 */
class SpacedRepetitionScheduleInterval extends Model
{
    /**
     * Custom table name for intervals.
     *
     * @var string
     */
    protected $table = 'spaced_repetition_schedule_interval';
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['interval_seconds', 'learning_step_forward', 'learning_step_back'];
}
