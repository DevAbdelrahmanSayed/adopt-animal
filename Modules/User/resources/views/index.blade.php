@extends('user::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('user.name') !!}</p>
@endsection
