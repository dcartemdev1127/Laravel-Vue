@extends('layouts.app_without_header')
@section('content')
    <login-component
        :error = "{{ json_encode($error) }}"
    />
@endsection