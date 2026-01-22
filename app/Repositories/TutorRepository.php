<?php

namespace App\Repositories;

use App\Models\Tutor;
use App\Repositories\Contracts\TutorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TutorRepository implements TutorRepositoryInterface
{
    public function getAllWithRelations(): Collection
    {
        return Tutor::query()
            ->with(['subjects', 'levels', 'availabilities'])
            ->get();
    }

    public function findBySubjectAndLevel(int $subjectId, int $levelId): Collection
    {
        return Tutor::query()
            ->whereHas('subjects', fn ($query) => $query->where('subjects.id', $subjectId))
            ->whereHas('levels', fn ($query) => $query->where('levels.id', $levelId))
            ->with(['subjects', 'levels', 'availabilities'])
            ->get();
    }
}
