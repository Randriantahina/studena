<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tutor extends Model
{
    /** @use HasFactory<\Database\Factories\TutorFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
    ];

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'tutor_subject', 'tutor_id', 'subject_id');
    }

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class, 'tutor_level', 'tutor_id', 'level_id');
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }
}
