<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            'Collège',
            'Lycée',
            'Terminale',
        ];

        foreach ($levels as $levelName) {
            Level::firstOrCreate(['name' => $levelName]);
        }
    }
}
