<?php

namespace App\Models\StudyTasks;

use App\Models\StudyTasks\StudyTask;
use Illuminate\Database\Eloquent\Model;

/**
 * Difficulty taxonomy for study tasks.
 */
class StudyTasksDifficultyLevel extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description'];

    /**
     * Tasks assigned this difficulty level.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(StudyTask::class, 'difficulty_level_id');
    }
}
