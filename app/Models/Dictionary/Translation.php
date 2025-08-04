<?php

namespace App\Models\Dictionary;

use App\Models\Languages\Language;
use App\Models\FlashCards\FlashCard;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dictionary\DictionarySense;

class Translation extends Model
{
    protected $fillable = [
        'dictionary_sense_id', 'translation_language_id',
        'translated_element_text', 'translated_meaning',
        'translated_synonyms', 'translated_examples', 'translated_how_to_use'
    ];

    public function sense()
    {
        return $this->belongsTo(DictionarySense::class, 'dictionary_sense_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'translation_language_id');
    }

    public function flashCards()
    {
        return $this->hasMany(FlashCard::class);
    }
}
