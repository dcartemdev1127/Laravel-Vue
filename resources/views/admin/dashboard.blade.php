@extends('admin.layouts.app')
@php
    $user = Auth::user();
@endphp

@section('content')
    <admin-dashboard-component
        :user = "{{ json_encode($user) }}"
        :data = "{{ json_encode($data) }}"
        :subject-types = "{{ json_encode($subjectTypes) }}"
        :expertise-types = "{{ json_encode($expertiseTypes) }}"
    />
@endsection