<?php

namespace App\Models\StudyTasks;

use App\Models\User;
use App\Models\StudyTasks\StudyTask;
use App\Models\StudyTasks\StudyTopic;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudyTasks\StudyTasksProgressStatus;

/**
 * Tracks a user's progress for a given study task within a topic.
 */
class UserStudyTaskProgress extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'study_topic_id', 'study_task_id', 'study_tasks_progress_status_id', 'user_id'
    ];

    /**
     * Topic context for this progress record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(StudyTopic::class, 'study_topic_id');
    }

    /**
     * Task this progress entry concerns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(StudyTask::class, 'study_task_id');
    }

    /**
     * Current progress status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(StudyTasksProgressStatus::class, 'study_tasks_progress_status_id');
    }

    /**
     * The user whose progress is being tracked.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
