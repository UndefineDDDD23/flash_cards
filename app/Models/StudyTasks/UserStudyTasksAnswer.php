<?php

namespace App\Models\StudyTasks;

use App\Models\StudyTasks\StudyTask;
use Illuminate\Database\Eloquent\Model;

class UserStudyTasksAnswer extends Model
{
    protected $fillable = ['study_task_id', 'text', 'description', 'correct'];

    public function task()
    {
        return $this->belongsTo(StudyTask::class, 'study_task_id');
    }
}
