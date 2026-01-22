<?php

namespace App\Repositories\Contracts;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

interface StudentRepositoryInterface
{
    /**
     * @return Collection<int, Student>
     */
    public function getAllWithRelations(): Collection;

    public function findByIdWithRelations(int $id): ?Student;
}
