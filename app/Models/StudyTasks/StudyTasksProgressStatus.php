<?php

namespace App\Models\StudyTasks;

use Illuminate\Database\Eloquent\Model;
use App\Models\StudyTasks\UserStudyTaskProgress;

class StudyTasksProgressStatus extends Model
{
    protected $fillable = ['name', 'description'];

    public function progress()
    {
        return $this->hasMany(UserStudyTaskProgress::class, 'study_tasks_progress_status_id');
    }
}
