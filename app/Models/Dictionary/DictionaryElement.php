<?php

namespace App\Models\Dictionary;

use App\Models\Languages\Language;
use App\Models\Dictionary\PartOfSpeech;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dictionary\DictionarySense;
use App\Models\Dictionary\DictionaryElementsType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DictionaryElement extends Model
{
    protected $fillable = [
        'language_id', 'dictionary_element_type_id', 'part_of_speech_id', 'element_text'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function type()
    {
        return $this->belongsTo(DictionaryElementsType::class, 'dictionary_element_type_id');
    }

    public function partOfSpeech()
    {
        return $this->belongsTo(PartOfSpeech::class);
    }

    public function senses()
    {
        return $this->hasMany(DictionarySense::class);
    }
}
