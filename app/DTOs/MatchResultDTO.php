<?php

namespace App\DTOs;

use App\Models\Student;

readonly class MatchResultDTO
{
    /**
     * @param  array<TutorMatchDTO>  $matches
     */
    public function __construct(
        public Student $student,
        public array $matches,
        public int $totalMatches,
    ) {}

    public function toArray(): array
    {
        return [
            'student' => [
                'id' => $this->student->id,
                'full_name' => $this->student->full_name,
                'level' => $this->student->level->name,
                'subjects' => $this->student->subjects->pluck('name')->toArray(),
            ],
            'total_matches' => $this->totalMatches,
            'matches' => array_map(fn (TutorMatchDTO $match) => $match->toArray(), $this->matches),
        ];
    }
}
