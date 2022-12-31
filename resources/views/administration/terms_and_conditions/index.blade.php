@extends('layouts.simple.master')
@section('title', 'Basic DataTables')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>
@endsection
<script src="https://cdn.ckeditor.com/4.18.0/standard/ckeditor.js"></script>

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Administrations</h3>
@endsection

@section('content')
<form method="POST" action="{{route('terms.store')}}">
    @csrf
    <textarea name="terms_and_conditions">@if (isset($last)) {{$last['terms_and_conditions']}} @endif</textarea>
    <button type="submit" class="btn btn-success my-3">Save</button>
</form>
@endsection


@section('script')
<script>
    CKEDITOR.replace( 'terms_and_conditions' );
</script>
@endsection
