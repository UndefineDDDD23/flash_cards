<?php

namespace App\Models\StudyTasks;

use App\Models\StudyTasks\StudyTopic;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudyTasks\StudyTasksType;
use App\Models\StudyTasks\UserStudyTasksAnswer;
use App\Models\StudyTasks\UserStudyTaskProgress;
use App\Models\StudyTasks\StudyTasksDifficultyLevel;

class StudyTask extends Model
{
    protected $fillable = [
        'study_topic_id', 'difficulty_level_id', 'task_type_id', 'title', 'description'
    ];

    public function topic()
    {
        return $this->belongsTo(StudyTopic::class, 'study_topic_id');
    }

    public function type()
    {
        return $this->belongsTo(StudyTasksType::class, 'task_type_id');
    }

    public function difficultyLevel()
    {
        return $this->belongsTo(StudyTasksDifficultyLevel::class, 'difficulty_level_id');
    }

    public function progress()
    {
        return $this->hasMany(UserStudyTaskProgress::class);
    }

    public function answers()
    {
        return $this->hasMany(UserStudyTasksAnswer::class);
    }
}
