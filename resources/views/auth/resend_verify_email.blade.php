@extends('layouts.master')

@php
    $emailValue = [
        'email' => Auth::user()->email
    ];
@endphp

@section('content')
    <resend-verify-email-component
        :email="{{ json_encode($emailValue) }}"
    />
@endsection