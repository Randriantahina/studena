<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Level;
use App\Models\Subject;
use App\Models\Tutor;
use Illuminate\Database\Seeder;

class TutorSeeder extends Seeder
{
    private const DAYS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private const TIME_SLOTS = [
        ['start' => '14:00', 'end' => '16:00'],
        ['start' => '16:00', 'end' => '18:00'],
        ['start' => '18:00', 'end' => '20:00'],
        ['start' => '10:00', 'end' => '12:00'],
        ['start' => '10:00', 'end' => '19:00'],
    ];

    public function run(): void
    {
        $subjects = Subject::all();
        $levels = Level::all();

        if ($subjects->isEmpty() || $levels->isEmpty()) {
            $this->command->error('Please run SubjectSeeder and LevelSeeder first!');

            return;
        }

        $tutorData = [
            ['name' => 'Ahmed', 'subjects' => ['Mathématiques'], 'levels' => ['Lycée'], 'availabilities' => [
                ['day' => 'Lundi', 'start' => '18:00', 'end' => '20:00'],
                ['day' => 'Mercredi', 'start' => '16:00', 'end' => '20:00'],
                ['day' => 'Samedi', 'start' => '10:00', 'end' => '19:00'],
            ]],
            ['name' => 'Sarah', 'subjects' => ['Physique'], 'levels' => ['Collège', 'Lycée'], 'availabilities' => [
                ['day' => 'Mercredi', 'start' => '14:00', 'end' => '16:00'],
                ['day' => 'Samedi', 'start' => '10:00', 'end' => '22:00'],
            ]],
            ['name' => 'Karim', 'subjects' => ['Français'], 'levels' => ['Terminale'], 'availabilities' => [
                ['day' => 'Lundi', 'start' => '18:00', 'end' => '20:00'],
            ]],
        ];

        foreach ($tutorData as $data) {
            $tutor = Tutor::create(['full_name' => $data['name']]);

            foreach ($data['subjects'] as $subjectName) {
                $subject = $subjects->firstWhere('name', $subjectName);
                if ($subject) {
                    $tutor->subjects()->attach($subject->id);
                }
            }

            foreach ($data['levels'] as $levelName) {
                $level = $levels->firstWhere('name', $levelName);
                if ($level) {
                    $tutor->levels()->attach($level->id);
                }
            }

            foreach ($data['availabilities'] as $avail) {
                Availability::create([
                    'tutor_id' => $tutor->id,
                    'day_of_week' => $avail['day'],
                    'start_time' => $avail['start'],
                    'end_time' => $avail['end'],
                ]);
            }
        }

        for ($i = 4; $i <= 10; $i++) {
            $tutor = Tutor::factory()->create();

            $randomSubjects = $subjects->random(rand(1, 3));
            $tutor->subjects()->attach($randomSubjects->pluck('id'));

            $randomLevels = $levels->random(rand(1, 2));
            $tutor->levels()->attach($randomLevels->pluck('id'));

            $numAvailabilities = rand(2, 4);
            $selectedDays = fake()->randomElements(self::DAYS, $numAvailabilities);

            foreach ($selectedDays as $day) {
                $timeSlot = fake()->randomElement(self::TIME_SLOTS);
                Availability::create([
                    'tutor_id' => $tutor->id,
                    'day_of_week' => $day,
                    'start_time' => $timeSlot['start'],
                    'end_time' => $timeSlot['end'],
                ]);
            }
        }
    }
}
