<?php

namespace App\Models\Dictionary;

use App\Models\Languages\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DictionaryElement extends Model
{
    protected $fillable = [
        'language_id', 
        'element_text', 
        'synonyms', 
        'meaning', 
        'examples', 
        'how_to_use'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function translations() {
        return $this->hasMany(Translation::class, 'dictionary_element_id');
    }
}
