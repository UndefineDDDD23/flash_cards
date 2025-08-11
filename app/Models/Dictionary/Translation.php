<?php

namespace App\Models\Dictionary;

use App\Models\Languages\Language;
use App\Models\FlashCards\FlashCard;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dictionary\DictionaryElement;

/**
 * Represents a localized translation of a dictionary element.
 *
 * Stores translated text and optional structured arrays for synonyms and
 * examples. Provides formatted accessors to present arrays as readable lines.
 */
class Translation extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dictionary_element_id', 
        'translation_language_id',
        'translated_element_text', 
        'translated_synonyms', 
        'translated_meaning', 
        'translated_examples', 
        'translated_how_to_use'
    ];

    /**
     * Attribute casting for structured fields.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'translated_synonyms' => 'array',
        'translated_examples' => 'array',
    ];

    /**
     * Target language of this translation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'translation_language_id');
    }

    /**
     * Source dictionary element this translation refers to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dictionaryElement()
    {
        return $this->belongsTo(DictionaryElement::class, 'dictionary_element_id');
    }

    /**
     * Flash cards generated for this translation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function flashCards()
    {
        return $this->hasMany(FlashCard::class);
    }

    /**
     * Present translated synonyms array in a human-readable multiline string.
     *
     * @return string
     */
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

    /**
     * Present translated examples array in a human-readable multiline string.
     *
     * @return string
     */
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
