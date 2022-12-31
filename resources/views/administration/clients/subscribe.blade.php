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
                <form class="form theme-form" action="{{route("clients.subscribe.store")}}" med method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="client_id">Client<span style="color:red;">*</span></label><br>
                                    <select name="client_id" id="clients" class="form-control input-air-primary">
                                    <option value="">Select a Client</option>
                                        @foreach($clients as $client)
                                            <option {{old('client_id') == $client->id ? 'selected' : ''}} value="{{$client->id}}">{{$client->name . "  (" . $client->email . ")"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="plan_id">Subscription Plan<span style="color:red;">*</span></label>
                                    <select name="plan_id" id="plan_id"   class="form-control input-air-primary" >
                                        <option>Select A Subscription Plan</option>
                                        @foreach($plans as $plan)
                                            <option {{old('plan_id') == $plan->id? 'selected' : ''}} value="{{$plan->id}}">{{"$plan->name   , price is $plan->currency  $plan->monthly_price , monthly message is  $plan->monthly_message/month"}} </option>
                                        @endforeach
                                    </select>
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
<script>
    $(document).ready(function () {
        $('#clients').select2();
    });

</script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
