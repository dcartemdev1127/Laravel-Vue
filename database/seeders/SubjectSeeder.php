<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    private array $subjects = [
        ['name' => 'Academic'],
        ['name' => 'Vocational'],
        ['name' => 'The Arts'],
        ['name' => 'Music'],
        ['name' => 'Sports'],
        ['name' => 'Extra Curricular'],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->subjects as $item) {
            $subjects = new Subject($item);
            $subjects->save();
        }
    }
}
