<?php

namespace App\Models\StudyTasks;

use Illuminate\Database\Eloquent\Model;
use App\Models\StudyTasks\UserStudyTaskProgress;

/**
 * Progress state for a user's task attempt (e.g., pending, in_progress, done).
 */
class StudyTasksProgressStatus extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description'];

    /**
     * User progress rows currently in this status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function progress()
    {
        return $this->hasMany(UserStudyTaskProgress::class, 'study_tasks_progress_status_id');
    }
}
