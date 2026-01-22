<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = [
        'name',
    ];

    public function tutors(): BelongsToMany
    {
        return $this->belongsToMany(Tutor::class, 'tutor_level', 'level_id', 'tutor_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
