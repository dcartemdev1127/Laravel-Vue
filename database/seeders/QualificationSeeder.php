<?php

namespace Database\Seeders;

use App\Models\Qualification;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{

    private array $qualifications = [
        ['name' => "Bachelor's Degree"],
        ['name' => "Master's Degree"],
        ['name' => "PhD"],
        ['name' => "Certification"]
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->qualifications as $item) {
            $qualifications = new Qualification($item);
            $qualifications->save();
        }
    }
}
