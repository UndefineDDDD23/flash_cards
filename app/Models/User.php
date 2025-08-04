<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use App\Models\Languages\Language;
use App\Models\FlashCards\FlashCard;
use Illuminate\Notifications\Notifiable;
use App\Models\StudyTasks\UserStudyTaskProgress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'studied_language_id', 
        'native_language_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function studiedLanguage()
    {
        return $this->belongsTo(Language::class, 'studied_language_id');
    }

    public function nativeLanguage()
    {
        return $this->belongsTo(Language::class, 'native_language_id');
    }

    public function flashCards()
    {
        return $this->hasMany(FlashCard::class);
    }

    public function studyTaskProgress()
    {
        return $this->hasMany(UserStudyTaskProgress::class);
    }
}
