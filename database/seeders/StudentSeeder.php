<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Level;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    private const DAYS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private const TIME_SLOTS = [
        ['start' => '14:00', 'end' => '16:00'],
        ['start' => '16:00', 'end' => '18:00'],
        ['start' => '18:00', 'end' => '20:00'],
        ['start' => '10:00', 'end' => '12:00'],
    ];

    public function run(): void
    {
        $subjects = Subject::all();
        $levels = Level::all();

        if ($subjects->isEmpty() || $levels->isEmpty()) {
            $this->command->error('Please run SubjectSeeder and LevelSeeder first!');

            return;
        }

        $studentData = [
            ['name' => 'Ali', 'subjects' => ['Mathématiques'], 'level' => 'Lycée', 'availabilities' => [
                ['day' => 'Lundi', 'start' => '18:00', 'end' => '20:00'],
            ]],
            ['name' => 'Yasmine', 'subjects' => ['Physique'], 'level' => 'Collège', 'availabilities' => [
                ['day' => 'Mercredi', 'start' => '14:00', 'end' => '16:00'],
            ]],
        ];

        foreach ($studentData as $data) {
            $level = $levels->firstWhere('name', $data['level']);
            if (! $level) {
                continue;
            }

            $student = Student::create([
                'full_name' => $data['name'],
                'level_id' => $level->id,
            ]);

            foreach ($data['subjects'] as $subjectName) {
                $subject = $subjects->firstWhere('name', $subjectName);
                if ($subject) {
                    $student->subjects()->attach($subject->id);
                }
            }

            foreach ($data['availabilities'] as $avail) {
                Availability::create([
                    'student_id' => $student->id,
                    'day_of_week' => $avail['day'],
                    'start_time' => $avail['start'],
                    'end_time' => $avail['end'],
                ]);
            }
        }

        for ($i = 3; $i <= 50; $i++) {
            $level = $levels->random();
            $student = Student::factory()->create([
                'level_id' => $level->id,
            ]);

            $randomSubjects = $subjects->random(rand(1, 2));
            $student->subjects()->attach($randomSubjects->pluck('id'));

            $numAvailabilities = rand(1, 3);
            $selectedDays = fake()->randomElements(self::DAYS, $numAvailabilities);

            foreach ($selectedDays as $day) {
                $timeSlot = fake()->randomElement(self::TIME_SLOTS);
                Availability::create([
                    'student_id' => $student->id,
                    'day_of_week' => $day,
                    'start_time' => $timeSlot['start'],
                    'end_time' => $timeSlot['end'],
                ]);
            }
        }
    }
}
