<?php

namespace App\Models\StudyTasks;

use App\Models\StudyTasks\StudyTopic;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudyTasks\StudyTasksType;
use App\Models\StudyTasks\UserStudyTasksAnswer;
use App\Models\StudyTasks\UserStudyTaskProgress;
use App\Models\StudyTasks\StudyTasksDifficultyLevel;

/**
 * Individual learning activity within a study topic.
 */
class StudyTask extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'study_topic_id', 'difficulty_level_id', 'task_type_id', 'title', 'description'
    ];

    /**
     * Parent topic this task belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(StudyTopic::class, 'study_topic_id');
    }

    /**
     * Type/category of the task (e.g., quiz, writing).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(StudyTasksType::class, 'task_type_id');
    }

    /**
     * Difficulty level of the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function difficultyLevel()
    {
        return $this->belongsTo(StudyTasksDifficultyLevel::class, 'difficulty_level_id');
    }

    /**
     * User progress entries for this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function progress()
    {
        return $this->hasMany(UserStudyTaskProgress::class);
    }

    /**
     * Possible answers associated with this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(UserStudyTasksAnswer::class);
    }
}
