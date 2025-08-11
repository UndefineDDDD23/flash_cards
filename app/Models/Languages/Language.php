<?php

namespace App\Models\Languages;

use App\Models\Dictionary\Translation;
use App\Models\User;
use App\Models\StudyTasks\StudyTopic;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dictionary\DictionaryElement;

/**
 * Language registry entry (e.g., English, Spanish) with ISO-like code.
 */
class Language extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'code'];

    /**
     * Users for whom this is the studied language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersStudying()
    {
        return $this->hasMany(User::class, 'studied_language_id');
    }

    /**
     * Users for whom this is the native language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersNative()
    {
        return $this->hasMany(User::class, 'native_language_id');
    }

    /**
     * Dictionary elements authored in this language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dictionaryElements()
    {
        return $this->hasMany(DictionaryElement::class);
    }
    
    /**
     * Translations whose target language is this language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->hasMany(Translation::class);
    }

    /**
     * Study topics written in this language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studyTopics()
    {
        return $this->hasMany(StudyTopic::class);
    }
}
