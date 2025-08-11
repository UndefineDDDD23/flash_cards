<?php

namespace App\Models\StudyTasks;

use App\Models\Languages\Language;
use App\Models\StudyTasks\StudyTask;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudyTasks\UserStudyTaskProgress;

/**
 * A subject area grouping related study tasks within a language.
 */
class StudyTopic extends Model
{
    /**
     * Mass-assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description', 'language_id'];

    /**
     * Language the topic belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Tasks within this topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(StudyTask::class);
    }

    /**
     * User progress records for tasks in this topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function progress()
    {
        return $this->hasMany(UserStudyTaskProgress::class);
    }
}
