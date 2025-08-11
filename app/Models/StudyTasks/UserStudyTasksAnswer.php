<?php

namespace App\Models\StudyTasks;

use App\Models\StudyTasks\StudyTask;
use Illuminate\Database\Eloquent\Model;

/**
 * Candidate or submitted answer for a study task.
 */
class UserStudyTasksAnswer extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['study_task_id', 'text', 'description', 'correct'];

    /**
     * Task this answer belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(StudyTask::class, 'study_task_id');
    }
}
