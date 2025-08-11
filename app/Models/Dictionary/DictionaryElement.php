<?php

namespace App\Models\Dictionary;

use App\Models\Languages\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a base dictionary entry (headword) in a specific language.
 *
 * A DictionaryElement stores raw lexical data (text, synonyms, meaning,
 * examples, usage notes) which may be translated into other languages.
 */
class DictionaryElement extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'language_id', 
        'element_text', 
        'synonyms', 
        'meaning', 
        'examples', 
        'how_to_use',
        'is_ai_generated'
    ];

    /**
     * Language this dictionary element belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Translations of this dictionary element into other languages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations() {
        return $this->hasMany(Translation::class, 'dictionary_element_id');
    }
}
