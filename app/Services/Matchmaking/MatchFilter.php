<?php

namespace App\Services\Matchmaking;

use App\DTOs\TutorMatchDTO;

readonly class MatchFilter
{
    /**
     * @param  array<TutorMatchDTO>  $matches
     * @return array<TutorMatchDTO>
     */
    public function filterNonZeroMatches(array $matches): array
    {
        return array_filter($matches, fn (TutorMatchDTO $match) => $match->compatibilityScore > 0);
    }
}
