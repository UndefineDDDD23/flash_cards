<?php

namespace App\Models\Dictionary;

use App\Models\Languages\Language;
use App\Models\FlashCards\FlashCard;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dictionary\DictionaryElement;

class Translation extends Model
{
    protected $fillable = [
        'dictionary_element_id', 
        'translation_language_id',
        'translated_element_text', 
        'translated_synonyms', 
        'translated_meaning', 
        'translated_examples', 
        'translated_how_to_use'
    ];

    protected $casts = [
        'translated_synonyms' => 'array',
        'translated_examples' => 'array',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'translation_language_id');
    }

    public function dictionaryElement()
    {
        return $this->belongsTo(DictionaryElement::class, 'dictionary_element_id');
    }

    public function flashCards()
    {
        return $this->hasMany(FlashCard::class);
    }

    public function getFormattedSynonymsAttribute()
    {
        $synonyms = $this->translated_synonyms;

        if (!is_array($synonyms)) {
            return '';
        }

        return collect($synonyms)
            ->map(fn($value, $key) => "$key — $value")
            ->implode("\n");
    }

    public function getFormattedExamplesAttribute()
    {
        $examples = $this->translated_examples;

        if (!is_array($examples)) {
            return '';
        }

        return collect($examples)
            ->map(fn($value, $key) => "$key — $value")
            ->implode("\n");
    }
}
