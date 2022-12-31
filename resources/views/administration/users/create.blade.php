@extends('layouts.simple.master')
@section('title', 'Base Inputs')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
@endsection

@section('breadcrumb-items')
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <form class="form theme-form" action="{{route("users.store")}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input name="name" class="form-control input-air-primary" value="{{old('name')}}" id="name" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="email">Email<span style="color:red;">*</span></label>
                                <input type="text" value="{{old('email')}}" name="email" class="form-control">
						        </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="password">Password<span style="color:red;">*</span></label>
                                <input type="password" name="password" value="{{old('password')}}" class="form-control">
                                <label for="" style="font-size:12px; color:gray;">password must contains capital and small letters , numbers and special character</label>
						        </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <input class="btn btn-light" type="reset" value="Cancel">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
