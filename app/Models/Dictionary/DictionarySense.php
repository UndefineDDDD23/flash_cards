<?php

namespace App\Models\Dictionary;

use App\Models\Dictionary\Translation;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dictionary\DictionaryElement;

class DictionarySense extends Model
{
    protected $fillable = [
        'dictionary_element_id', 'how_to_use', 'examples', 'synonyms',
        'meaning', 'sense_order', 'phonetic_transcription'
    ];

    public function dictionaryElement()
    {
        return $this->belongsTo(DictionaryElement::class);
    }

    public function translations()
    {
        return $this->hasMany(Translation::class);
    }
}
