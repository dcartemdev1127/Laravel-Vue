<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\SubjectItem;
use Illuminate\Database\Seeder;

class SubjectItemSeeder extends Seeder
{
    private array $subjectItems = [
        "Academic" => [
            "Mathematics",
            "English",
            "Verbal Reasoning",
            "Non Verbal Reasoning",
            "Science",
            "History",
            "Geography",
            "Religious Studies",
            "French",
            "German",
            "Spanish",
            "Italian",
            "Mandarin",
            "Japanese",
            "Latin",
            "Ancient Greek",
            "Arabic",
            "Portuguese",
            "Russian",
            "Urdu",
            "Bengali",
            "Panjabi",
            "Polish",
            "Dutch",
            "Sociology",
            "Psychology",
            "Economics",
            "Business Studies",
            "Physical Education (PE)",
            "Sports Studies",
            "Citizenship Studies",
            "Media Studies",
            "Health and Social Care",
            "Statistics",
            "Environmental Science",
            "Astronomy",
            "Philosophy",
            "Classics",
            "Science",
            "Biology",
            "Chemistry",
            "Physics",
            "Further Mathematics",
            "Law",
            "Environmental Science",
            "Computer Science",
            "Politics",
            "Classical Civilisatio"
        ],
        "Vocational" => [
            "Engineering",
            "Construction",
            "Health and Social Care",
            "Travel and Tourism",
            "Child Development",
            "Agriculture",
            "Catering",
            "Business and Administration"
        ],
        "The Arts" => [
            "Fine Art",
            "Design",
            "Drama",
            "Dance",
            "Photography",
            "Film Studies",
            "Graphic Communication",
            "History of Art",
            "Painting",
            "Drawing"
        ],
        "Music" => [
            "Guitar",
            "Piano",
            "Clarinet",
            "Saxophone",
            "Drums",
            "Flute",
            "Violin",
            "Recorder",
            "Trumpet",
            "Harp",
            "Cello",
            "Oboe",
            "Accordion",
            "Bagpipes",
            "The French Horn",
            "Banjo",
            "Singing"
        ],
        "Sports" => [
            "Football",
            "Basketball",
            "Tennis",
            "Cricket",
            "Golf",
            "Rugby",
            "Badminton",
            "Squash",
            "Boxing",
            "Athletics",
            "American Football",
            "Baseball",
            "Cycling",
            "Skiing",
            "Wakeboarding",
            "Sailing",
            "Go Karting",
            "Water Polo",
            "Polo",
            "Archery",
            "Yoga",
            "Gym",
            "Table Tennis",
            "Padel"
        ],
        "Extra Curricular" => [
            "Chess",
            "Sewing",
            "Script Writing",
            "Dungeons and Dragons",
            "Warhammer",
            "Esports",
            "Conservation",
            "Stable Management",
            "Bee Keeping",
            "Krochet",
            "Hiking",
            "Gardening",
            "Origami",
            "Candle Making",
            "Hiking",
            "Parkour",
            "Running",
            "Calligraphy",
            "Wood Working",
            "Cooking"
        ]
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->subjectItems as $key => $item) {
            $subjectId = Subject::where('name', $key)->first();
            if($subjectId) {
                foreach($item as $subItem) {
                    $subjectItem = new SubjectItem([
                        'subject_id' => $subjectId->id,
                        'name' => $subItem
                    ]);
                    $subjectItem->save();
                }
            }
        }
    }
}
