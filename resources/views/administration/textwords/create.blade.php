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
                <form class="form theme-form" action="{{route("textwords.store")}}" med method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="title">Title<span style="color:red;">*</span></label>
                                    <input name="title" value="{{old('title')}}" class="form-control input-air-primary" id="title" type="text" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="description">Description<span style="color:red;">*</span></label>
                                    <input name="description" value="{{old('description')}}" class="form-control input-air-primary" id="description" type="text" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="monthly_message_limit">Monthly message limit<span style="color:red;">*</span></label>
                                    <input name="monthly_message_limit" value="{{old('monthly_message_limit')}}" class="form-control input-air-primary" id="monthly_message_limit" type="number" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="use_case_id">Use Case<span style="color:red;">*</span></label>
                                    <select name="use_case_id"  class="form-control input-air-primary">
                                        @foreach($useCases as $useCase)
                                            <option {{old('use_case_id') == $useCase->id ? 'selected' : ''}} value="{{$useCase->id}}">{{$useCase->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="client_id">Client<span style="color:red;">*</span></label>
                                    <select name="client_id"  class="form-control input-air-primary">
                                        @foreach($clients as $client)
                                            <option {{old('client_id') == $client->id ? 'selected' : ''}} value="{{$client->id}}">{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <input class="btn btn-light" onclick="document.location='/admin/textwords'" type='button' value="Cancel">
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
