<?php

namespace App\Models\Languages;

use App\Models\User;
use App\Models\StudyTasks\StudyTopic;
use App\Models\Dictionary\PartOfSpeech;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dictionary\DictionaryElement;

class Language extends Model
{
    protected $fillable = ['name', 'code'];

    public function usersStudying()
    {
        return $this->hasMany(User::class, 'studied_language_id');
    }

    public function usersNative()
    {
        return $this->hasMany(User::class, 'native_language_id');
    }

    public function partsOfSpeech()
    {
        return $this->hasMany(PartOfSpeech::class);
    }

    public function dictionaryElements()
    {
        return $this->hasMany(DictionaryElement::class);
    }

    public function studyTopics()
    {
        return $this->hasMany(StudyTopic::class);
    }
}
