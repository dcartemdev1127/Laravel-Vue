<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    private array $languages = [
        ['name' => 'English'],
        ['name' => 'Spanish'],
        ['name' => 'Mandarin Chinese'],
        ['name' => 'French'],
        ['name' => 'German'],
        ['name' => 'Arabic'],
        ['name' => 'Russian'],
        ['name' => 'Japanese'],
        ['name' => 'Portuguese'],
        ['name' => 'Hindi']
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->languages as $item) {
            $languages = new Language($item);
            $languages->save();
        }
    }
}
