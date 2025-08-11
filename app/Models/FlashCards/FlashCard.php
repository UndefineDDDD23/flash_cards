<?php

namespace App\Models\FlashCards;

use App\Models\User;
use App\Models\Dictionary\Translation;
use Illuminate\Database\Eloquent\Model;
use App\Models\FlashCards\FlashCardsStatus;

class FlashCard extends Model
{
    protected $fillable = [
        'user_id',
        'status_id',
        'user_meaning_text',
        'user_dictionary_element_text',
        'translation_id',
    ];


    public function translation()
    {
        return $this->belongsTo(Translation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(FlashCardsStatus::class);
    }
}
