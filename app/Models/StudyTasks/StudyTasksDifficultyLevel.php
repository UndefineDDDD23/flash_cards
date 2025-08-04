<?php

namespace App\Models\StudyTasks;

use App\Models\StudyTasks\StudyTask;
use Illuminate\Database\Eloquent\Model;

class StudyTasksDifficultyLevel extends Model
{
    protected $fillable = ['name', 'description'];

    public function tasks()
    {
        return $this->hasMany(StudyTask::class, 'difficulty_level_id');
    }
}
