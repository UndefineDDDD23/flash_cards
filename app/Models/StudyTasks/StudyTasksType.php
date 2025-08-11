<?php

namespace App\Models\StudyTasks;

use App\Models\StudyTasks\StudyTask;
use Illuminate\Database\Eloquent\Model;

/**
 * Enumeration of study task types (e.g., multiple-choice, translation).
 */
class StudyTasksType extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description'];

    /**
     * Tasks that are categorized under this type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(StudyTask::class, 'task_type_id');
    }
}
