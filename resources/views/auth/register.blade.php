@extends('layouts.app_without_header')
@section('content')
    <register-component
        :error = "{{ json_encode($error) }}"
        :user = "{{ json_encode($user) }}"
        :expertise-types = "{{ json_encode($expertiseTypes) }}"
        :language-types = "{{ json_encode($languageTypes) }}"
        :qualification-types = "{{ json_encode($qualificationTypes) }}"
        :subject-types = "{{ json_encode($subjectTypes) }}"
    />
@endsection