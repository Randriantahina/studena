<?php

namespace App\Rules\Matchmaking;

use App\Models\Student;
use App\Models\Tutor;

readonly class LevelMatchingRule
{
    public function calculateScore(Student $student, Tutor $tutor): int
    {
        $studentLevelId = $student->level_id;
        $tutorLevelIds = $tutor->levels->pluck('id')->toArray();

        if (in_array($studentLevelId, $tutorLevelIds, true)) {
            return 100;
        }

        return 0;
    }

    public function isMatch(Student $student, Tutor $tutor): bool
    {
        return $this->calculateScore($student, $tutor) === 100;
    }
}
