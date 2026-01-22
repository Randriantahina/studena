<?php

namespace App\Services\Matchmaking;

use App\Rules\Matchmaking\AvailabilityMatchingRule;
use App\Rules\Matchmaking\LevelMatchingRule;
use App\Rules\Matchmaking\SubjectMatchingRule;

readonly class CompatibilityScoreCalculator
{
    private const SUBJECT_WEIGHT = 40;

    private const LEVEL_WEIGHT = 30;

    private const AVAILABILITY_WEIGHT = 30;

    public function __construct(
        private SubjectMatchingRule $subjectRule,
        private LevelMatchingRule $levelRule,
        private AvailabilityMatchingRule $availabilityRule,
    ) {}

    public function calculate(int $subjectScore, int $levelScore, int $availabilityScore): int
    {
        return (int) round(
            ($subjectScore * self::SUBJECT_WEIGHT / 100) +
            ($levelScore * self::LEVEL_WEIGHT / 100) +
            ($availabilityScore * self::AVAILABILITY_WEIGHT / 100)
        );
    }
}
