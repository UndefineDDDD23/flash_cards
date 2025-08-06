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
        'description', 
        'element_text'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function translation() {
        return $this->hasMany(Translation::class);
    }
}
