<?php

namespace App\Models\Dictionary;

use App\Models\Languages\Language;
use App\Models\FlashCards\FlashCard;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'dictionary_element_id', 
        'translation_language_id',
        'translated_element_text', 
        'translated_description'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'translation_language_id');
    }

    public function flashCards()
    {
        return $this->hasMany(FlashCard::class);
    }
}
