<?php

namespace App\Models\Dictionary;

use App\Models\Languages\Language;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dictionary\DictionaryElement;

class PartOfSpeech extends Model
{
    protected $fillable = ['name', 'description', 'language_id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function dictionaryElements()
    {
        return $this->hasMany(DictionaryElement::class);
    }
}
