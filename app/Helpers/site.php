<?php


// TODO move to config

use App\Models\Subject;

function generateRandomPasscode() {
    $number = rand(0, 9999);
    return str_pad($number, 4, '0', STR_PAD_LEFT);
}

if(!function_exists('getSubjectTypes')) {
    function getSubjectTypes() {
        $subjects = Subject::all('id', 'name');
        $subjectTypes = [];
        foreach($subjects as $subject) {
            array_push($subjectTypes, [
                'id' => 0,
                'name' => $subject->name,
                'selectable' => false
            ]);
            foreach($subject->subjectItems()->orderBy('order_by', 'desc')->orderBy('id')->get() as $subjectItem) {
                array_push($subjectTypes, [
                    'id' => $subjectItem->id,
                    'name' => $subjectItem->name,
                    'selectable' => true
                ]);
            }
        }
        return $subjectTypes;
    }
}
