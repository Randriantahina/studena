<?php

namespace App\Rules\Matchmaking;

use App\Models\Student;
use App\Models\Tutor;

readonly class SubjectMatchingRule
{
    public function calculateScore(Student $student, Tutor $tutor): int
    {
        $studentSubjectIds = $student->subjects->pluck('id')->toArray();
        $tutorSubjectIds = $tutor->subjects->pluck('id')->toArray();

        if (empty($studentSubjectIds) || empty($tutorSubjectIds)) {
            return 0;
        }

        $commonSubjects = count(array_intersect($studentSubjectIds, $tutorSubjectIds));
        $totalStudentSubjects = count($studentSubjectIds);

        if ($commonSubjects === 0) {
            return 0;
        }

        return (int) round(($commonSubjects / $totalStudentSubjects) * 100);
    }

    public function getMatchedSubjects(Student $student, Tutor $tutor): array
    {
        return $student->subjects
            ->pluck('id')
            ->intersect($tutor->subjects->pluck('id'))
            ->map(fn ($id) => $tutor->subjects->firstWhere('id', $id)?->name)
            ->filter()
            ->values()
            ->toArray();
    }
}
