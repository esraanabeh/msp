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
                    <div class="col-12 card card-primary">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Name :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$feedback->first_name." ".$feedback->last_name}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Mobile :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$feedback->mobile}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Email :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$feedback->email}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Reason :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$feedback->reason}} </label>
                            </div>
                            @if ($feedback->organization)
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Organization :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$feedback->organization}} </label>
                            </div>
                            @endif
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Message :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$feedback->message}} </label>
                            </div>

                        </div>
                        </div>
                    </div>
            </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
