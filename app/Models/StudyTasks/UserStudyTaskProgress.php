<?php

namespace App\Models\StudyTasks;

use App\Models\User;
use App\Models\StudyTasks\StudyTask;
use App\Models\StudyTasks\StudyTopic;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudyTasks\StudyTasksProgressStatus;

class UserStudyTaskProgress extends Model
{
    protected $fillable = [
        'study_topic_id', 'study_task_id', 'study_tasks_progress_status_id', 'user_id'
    ];

    public function topic()
    {
        return $this->belongsTo(StudyTopic::class, 'study_topic_id');
    }

    public function task()
    {
        return $this->belongsTo(StudyTask::class, 'study_task_id');
    }

    public function status()
    {
        return $this->belongsTo(StudyTasksProgressStatus::class, 'study_tasks_progress_status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
