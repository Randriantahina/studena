<?php

namespace App\Services;

use App\DTOs\MatchResultDTO;
use App\DTOs\TutorMatchDTO;
use App\Models\Student;
use App\Models\Tutor;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\TutorRepositoryInterface;
use Illuminate\Support\Collection;

class MatchmakingService
{
    private const SUBJECT_WEIGHT = 40;

    private const LEVEL_WEIGHT = 30;

    private const AVAILABILITY_WEIGHT = 30;

    public function __construct(
        private TutorRepositoryInterface $tutorRepository,
        private StudentRepositoryInterface $studentRepository,
    ) {}

    public function findMatchesForStudent(int $studentId): ?MatchResultDTO
    {
        $student = $this->studentRepository->findByIdWithRelations($studentId);

        if (! $student) {
            return null;
        }

        $tutors = $this->tutorRepository->getAllWithRelations();
        $matches = $this->calculateMatches($student, $tutors);

        return new MatchResultDTO(
            student: $student,
            matches: $matches,
            totalMatches: count($matches),
        );
    }

    public function findMatchesForAllStudents(): array
    {
        $students = $this->studentRepository->getAllWithRelations();
        $tutors = $this->tutorRepository->getAllWithRelations();

        $results = [];

        foreach ($students as $student) {
            $matches = $this->calculateMatches($student, $tutors);
            $results[] = new MatchResultDTO(
                student: $student,
                matches: $matches,
                totalMatches: count($matches),
            );
        }

        return $results;
    }

    /**
     * @param  Collection<int, Tutor>  $tutors
     * @return array<TutorMatchDTO>
     */
    private function calculateMatches(Student $student, Collection $tutors): array
    {
        $matches = [];

        foreach ($tutors as $tutor) {
            $match = $this->calculateMatch($student, $tutor);

            if ($match->compatibilityScore > 0) {
                $matches[] = $match;
            }
        }

        usort($matches, fn (TutorMatchDTO $a, TutorMatchDTO $b) => $b->compatibilityScore <=> $a->compatibilityScore);

        return $matches;
    }

    private function calculateMatch(Student $student, Tutor $tutor): TutorMatchDTO
    {
        $subjectScore = $this->calculateSubjectScore($student, $tutor);
        $levelScore = $this->calculateLevelScore($student, $tutor);
        $availabilityData = $this->calculateAvailabilityScore($student, $tutor);

        $totalScore = (int) round(
            ($subjectScore * self::SUBJECT_WEIGHT / 100) +
            ($levelScore * self::LEVEL_WEIGHT / 100) +
            ($availabilityData['score'] * self::AVAILABILITY_WEIGHT / 100)
        );

        $matchedSubjects = $student->subjects
            ->pluck('id')
            ->intersect($tutor->subjects->pluck('id'))
            ->map(fn ($id) => $tutor->subjects->firstWhere('id', $id)?->name)
            ->filter()
            ->values()
            ->toArray();

        return new TutorMatchDTO(
            tutor: $tutor,
            compatibilityScore: $totalScore,
            matchedSubjects: $matchedSubjects,
            levelMatch: $levelScore === 100,
            commonAvailabilities: $availabilityData['common'],
            availabilityScore: $availabilityData['score'],
        );
    }

    private function calculateSubjectScore(Student $student, Tutor $tutor): int
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

    private function calculateLevelScore(Student $student, Tutor $tutor): int
    {
        $studentLevelId = $student->level_id;
        $tutorLevelIds = $tutor->levels->pluck('id')->toArray();

        if (in_array($studentLevelId, $tutorLevelIds, true)) {
            return 100;
        }

        return 0;
    }

    /**
     * @return array{score: int, common: array<int, array{day: string, start: string, end: string}>}
     */
    private function calculateAvailabilityScore(Student $student, Tutor $tutor): array
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
