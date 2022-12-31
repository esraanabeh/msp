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
<form method="POST" action="{{route('privacy.save')}}">
    @csrf
    <textarea name="privacy-policy">@if (isset($last)) {{$last['privacy-policy']}} @endif</textarea>
    <button type="submit" class="btn btn-success my-3">Save</button>
</form>
@endsection


@section('script')
<script>
    CKEDITOR.replace( 'privacy-policy' );
</script>
@endsection