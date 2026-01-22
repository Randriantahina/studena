<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StudentRepository implements StudentRepositoryInterface
{
    public function getAllWithRelations(): Collection
    {
        return Student::query()
            ->with(['level', 'subjects', 'availabilities'])
            ->get();
    }

    public function findByIdWithRelations(int $id): ?Student
    {
        return Student::query()
            ->with(['level', 'subjects', 'availabilities'])
            ->find($id);
    }
}
