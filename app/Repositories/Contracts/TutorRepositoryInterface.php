<?php

namespace App\Repositories\Contracts;

use App\Models\Tutor;
use Illuminate\Database\Eloquent\Collection;

interface TutorRepositoryInterface
{
    /**
     * @return Collection<int, Tutor>
     */
    public function getAllWithRelations(): Collection;

    /**
     * @return Collection<int, Tutor>
     */
    public function findBySubjectAndLevel(int $subjectId, int $levelId): Collection;
}
