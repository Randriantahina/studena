<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    protected $fillable = [
        'name',
    ];

    public function tutors(): BelongsToMany
    {
        return $this->belongsToMany(Tutor::class, 'tutor_subject', 'subject_id', 'tutor_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_subject', 'subject_id', 'student_id');
    }
}
