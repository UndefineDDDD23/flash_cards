<?php

namespace App\Models\FlashCards;

use App\Models\FlashCards\FlashCard;
use Illuminate\Database\Eloquent\Model;

class FlashCardsStatus extends Model
{
    protected $fillable = ['name', 'description'];

    public function flashCards()
    {
        return $this->hasMany(FlashCard::class, 'status_id');
    }
}
