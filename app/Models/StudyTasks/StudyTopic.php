<?php

namespace App\Models\StudyTasks;

use App\Models\Languages\Language;
use App\Models\StudyTasks\StudyTask;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudyTasks\UserStudyTaskProgress;

class StudyTopic extends Model
{
    protected $fillable = ['name', 'description', 'language_id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function tasks()
    {
        return $this->hasMany(StudyTask::class);
    }

    public function progress()
    {
        return $this->hasMany(UserStudyTaskProgress::class);
    }
}
