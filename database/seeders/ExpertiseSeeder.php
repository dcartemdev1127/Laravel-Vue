<?php

namespace Database\Seeders;

use App\Models\Expertise;
use Illuminate\Database\Seeder;

class ExpertiseSeeder extends Seeder
{
    private array $expertises = [
        ['name' => 'Tutor'],
        ['name' => 'Mentor'],
        ['name' => 'Coach'],
        ['name' => 'Governess/Governor'],
        ['name' => 'World Tutor'],
        ['name' => 'Travel Tutor'],
        ['name' => 'Super Tutor'],
        ['name' => 'Homeschool Tutor'],
        ['name' => 'Consultant']
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->expertises as $item) {
            $expertise = new Expertise($item);
            $expertise->save();
        }
    }
}
