@extends('layouts.simple.master')
@section('title', 'Base Inputs')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
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
                            <div class="d-flex justify-content-end">
                            <a href="{{route('contacts.edit',$contact->id)}}">
                                <button class="btn btn-square btn-primary active" type="button">Edit</button>
                            </a>
                        </div>

                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Name :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$contact->name}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Email :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$contact->email}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Mobile :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$contact->mobile}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Textword :</label>
                                <label
                                    class="col-sm-8 col-form-label"><a href="{{route('textwords.show',$contact->textword->id)}}">{{$contact->textword->title}}</a> </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Client :</label>
                                <label
                                    class="col-sm-8 col-form-label"><a href="{{route('clients.show',$contact->textword->client->id)}}">{{$contact->textword->client->name}}</a> </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Opt out date:</label>
                                <label
                                class="col-sm-8 col-form-label">{{$contact->optout_at}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Date of Birth :</label>
                                <label
                                class="col-sm-8 col-form-label">{{$contact->dob}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">City :</label>
                                <label
                                class="col-sm-8 col-form-label">{{$contact->city}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">State :</label>
                                <label
                                class="col-sm-8 col-form-label">{{$contact->state}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Zip code :</label>
                                <label
                                class="col-sm-8 col-form-label">{{$contact->zip_code}} </label>
                            </div>
                        </div>
                    </div>


</div>
</div>
@endsection

@section('script')

@endsection

