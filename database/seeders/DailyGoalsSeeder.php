<?php

namespace Database\Seeders;

use App\Models\DailyGoals;
use App\Models\User;
use App\Models\WaterIntake;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyGoalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "name" => "Adi Kurniawan",
            "email" => "adi@gmail.com",
            "password" => bcrypt("password"),
        ]);

        DailyGoals::factory(20)
        ->for($user, 'user') // Pastikan relasi "user" ada di model DailyGoals
        ->has(WaterIntake::factory(5), 'water_intakes') // Gunakan nama relasi
        ->create();
    }
}
