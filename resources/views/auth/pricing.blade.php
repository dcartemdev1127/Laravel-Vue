@extends('layouts.app_without_header')

@section('content')
    <register-pricing-component
        :packages = "{{ json_encode($packages) }}"
        :user = "{{ json_encode($user) }}"
    />
@endsection