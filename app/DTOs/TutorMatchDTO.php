<?php

namespace App\DTOs;

use App\Models\Tutor;

readonly class TutorMatchDTO
{
    public function __construct(
        public Tutor $tutor,
        public int $compatibilityScore,
        public array $matchedSubjects,
        public bool $levelMatch,
        public array $commonAvailabilities,
        public int $availabilityScore,
    ) {}

    public function toArray(): array
    {
        return [
            'tutor' => [
                'id' => $this->tutor->id,
                'full_name' => $this->tutor->full_name,
                'subjects' => $this->tutor->subjects->pluck('name')->toArray(),
                'levels' => $this->tutor->levels->pluck('name')->toArray(),
            ],
            'compatibility_score' => $this->compatibilityScore,
            'matched_subjects' => $this->matchedSubjects,
            'level_match' => $this->levelMatch,
            'common_availabilities' => $this->commonAvailabilities,
            'availability_score' => $this->availabilityScore,
        ];
    }
}
