@extends('members::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('members.name') !!}
    </p>
@endsection
