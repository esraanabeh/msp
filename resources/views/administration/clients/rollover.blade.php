@extends('layouts.simple.master')
@section('title', 'Base Inputs')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
<style>
.currentCredit{
    margin: 10px 30px;
    color: gray;
}
</style>
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
                <span class="currentCredit"> Current Credit : {{$subscription[0]->current_rollovers}}</span>
                <form class="form theme-form" action="{{route("clients.rollover.edit",$client->id)}}" med method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="action">Action<span style="color:red;">*</span></label><br>
                                    <select name="action" id="action" class="form-control input-air-primary">
                                    <option value="" disabled>Select a Action</option>
                                    <option value="Increase" selected>Increase</option>
                                    <option value="Decrease" >Decrease</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="amount" >Amount<span style="color:red;">*</span></label>
                                    <input  type="number" name="amount" id="amount" min="1"  class="form-control input-air-primary" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <input class="btn btn-light" onclick="document.location='/admin/clients'" type='button' value="Cancel">
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
