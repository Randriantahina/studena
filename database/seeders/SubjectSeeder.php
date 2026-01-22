<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'Mathématiques',
            'Physique',
            'Français',
            'Anglais',
            'Histoire',
            'Géographie',
            'Sciences',
            'Chimie',
            'Biologie',
            'Philosophie',
        ];

        foreach ($subjects as $subjectName) {
            Subject::firstOrCreate(['name' => $subjectName]);
        }
    }
}
