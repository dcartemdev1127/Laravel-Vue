@extends('layouts.master')

@section('content')
    <dashboard-component
        :user="{{ json_encode($user) }}"
        :data="{{ json_encode($data) }}"
        :bookings = "{{ json_encode($bookings) }}"
        :subject-types = "{{ json_encode($subjectTypes) }}"
    />
@endsection