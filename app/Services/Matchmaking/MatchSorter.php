<?php

namespace App\Services\Matchmaking;

use App\DTOs\TutorMatchDTO;

readonly class MatchSorter
{
    /**
     * @param  array<TutorMatchDTO>  $matches
     * @return array<TutorMatchDTO>
     */
    public function sortByCompatibilityScore(array $matches): array
    {
        usort($matches, fn (TutorMatchDTO $a, TutorMatchDTO $b) => $b->compatibilityScore <=> $a->compatibilityScore);

        return $matches;
    }
}
