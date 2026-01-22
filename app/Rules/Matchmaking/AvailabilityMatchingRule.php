<?php

namespace App\Rules\Matchmaking;

use App\Models\Student;
use App\Models\Tutor;

readonly class AvailabilityMatchingRule
{
    /**
     * @return array{score: int, common: array<int, array{day: string, start: string, end: string}>}
     */
    public function calculateScore(Student $student, Tutor $tutor): array
    {
        $studentAvailabilities = $student->availabilities;
        $tutorAvailabilities = $tutor->availabilities;

        if ($studentAvailabilities->isEmpty() || $tutorAvailabilities->isEmpty()) {
            return ['score' => 0, 'common' => []];
        }

        $commonAvailabilities = [];
        $totalOverlapMinutes = 0;
        $totalStudentMinutes = 0;

        foreach ($studentAvailabilities as $studentAvail) {
            $studentMinutes = $this->calculateMinutesBetween(
                $studentAvail->start_time,
                $studentAvail->end_time
            );
            $totalStudentMinutes += $studentMinutes;

            foreach ($tutorAvailabilities as $tutorAvail) {
                if ($studentAvail->day_of_week === $tutorAvail->day_of_week) {
                    $overlap = $this->calculateTimeOverlap(
                        $studentAvail->start_time,
                        $studentAvail->end_time,
                        $tutorAvail->start_time,
                        $tutorAvail->end_time
                    );

                    if ($overlap > 0) {
                        $totalOverlapMinutes += $overlap;

                        $commonAvailabilities[] = [
                            'day' => $studentAvail->day_of_week,
                            'start' => $this->getMaxTime($studentAvail->start_time, $tutorAvail->start_time),
                            'end' => $this->getMinTime($studentAvail->end_time, $tutorAvail->end_time),
                        ];
                    }
                }
            }
        }

        if ($totalStudentMinutes === 0) {
            return ['score' => 0, 'common' => $commonAvailabilities];
        }

        $score = (int) round(($totalOverlapMinutes / $totalStudentMinutes) * 100);

        return [
            'score' => min($score, 100),
            'common' => $this->deduplicateAvailabilities($commonAvailabilities),
        ];
    }

    private function calculateTimeOverlap(string $start1, string $end1, string $start2, string $end2): int
    {
        $start1Minutes = $this->timeToMinutes($start1);
        $end1Minutes = $this->timeToMinutes($end1);
        $start2Minutes = $this->timeToMinutes($start2);
        $end2Minutes = $this->timeToMinutes($end2);

        $overlapStart = max($start1Minutes, $start2Minutes);
        $overlapEnd = min($end1Minutes, $end2Minutes);

        return max(0, $overlapEnd - $overlapStart);
    }

    private function calculateMinutesBetween(string $start, string $end): int
    {
        $startMinutes = $this->timeToMinutes($start);
        $endMinutes = $this->timeToMinutes($end);

        return $endMinutes - $startMinutes;
    }

    private function timeToMinutes(string $time): int
    {
        [$hours, $minutes] = explode(':', $time);

        return (int) $hours * 60 + (int) $minutes;
    }

    private function getMaxTime(string $time1, string $time2): string
    {
        return $this->timeToMinutes($time1) > $this->timeToMinutes($time2) ? $time1 : $time2;
    }

    private function getMinTime(string $time1, string $time2): string
    {
        return $this->timeToMinutes($time1) < $this->timeToMinutes($time2) ? $time1 : $time2;
    }

    /**
     * @param  array<int, array{day: string, start: string, end: string}>  $availabilities
     * @return array<int, array{day: string, start: string, end: string}>
     */
    private function deduplicateAvailabilities(array $availabilities): array
    {
        $unique = [];

        foreach ($availabilities as $avail) {
            $key = $avail['day'].'-'.$avail['start'].'-'.$avail['end'];

            if (! isset($unique[$key])) {
                $unique[$key] = $avail;
            }
        }

        return array_values($unique);
    }
}
