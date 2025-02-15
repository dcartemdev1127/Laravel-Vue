@extends('layouts.app')

@section('content')
    <home-component
        :subject-types = "{{ json_encode($subjectTypes) }}"
        :tutors = "{{ json_encode($tutors) }}"
    />
@endsection
