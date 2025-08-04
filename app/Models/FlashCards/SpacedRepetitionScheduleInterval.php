<?php

namespace App\Models\FlashCards;

use Illuminate\Database\Eloquent\Model;

class SpacedRepetitionScheduleInterval extends Model
{
    protected $table = 'spaced_repetition_schedule_interval';
    protected $fillable = ['interval_seconds', 'learning_step_forward', 'learning_step_back'];
}
