<?php

namespace App\Services\Matchmaking;

use App\DTOs\TutorMatchDTO;
use App\Models\Student;
use App\Models\Tutor;
use App\Rules\Matchmaking\AvailabilityMatchingRule;
use App\Rules\Matchmaking\LevelMatchingRule;
use App\Rules\Matchmaking\SubjectMatchingRule;

readonly class MatchCalculator
{
    public function __construct(
        private SubjectMatchingRule $subjectRule,
        private LevelMatchingRule $levelRule,
        private AvailabilityMatchingRule $availabilityRule,
        private CompatibilityScoreCalculator $scoreCalculator,
    ) {}

    public function calculate(Student $student, Tutor $tutor): TutorMatchDTO
    {
        $subjectScore = $this->subjectRule->calculateScore($student, $tutor);
        $levelScore = $this->levelRule->calculateScore($student, $tutor);
        $availabilityData = $this->availabilityRule->calculateScore($student, $tutor);

        $totalScore = $this->scoreCalculator->calculate(
            $subjectScore,
            $levelScore,
            $availabilityData['score']
        );

        $matchedSubjects = $this->subjectRule->getMatchedSubjects($student, $tutor);

        return new TutorMatchDTO(
            tutor: $tutor,
            compatibilityScore: $totalScore,
            matchedSubjects: $matchedSubjects,
            levelMatch: $this->levelRule->isMatch($student, $tutor),
            commonAvailabilities: $availabilityData['common'],
            availabilityScore: $availabilityData['score'],
        );
    }
}
