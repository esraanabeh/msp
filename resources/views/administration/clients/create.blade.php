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
                <form class="form theme-form" action="{{route("clients.store")}}" med method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="first_name">Name<span style="color:red;">*</span></label>
                                    <input name="first_name" value="{{old('first_name')}}" class="form-control input-air-primary" id="first_name" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="last_name">Last Name<span style="color:red;">*</span></label>
                                    <input name="last_name" value="{{old('last_name')}}" class="form-control input-air-primary" id="last_name" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="email">Email<span style="color:red;">*</span></label>
                                <input type="text" name="email" value="{{old('email')}}" class="form-control">
						        </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="mobile">Mobile<span style="color:red;">*</span></label>
                                    <input id="mobileMasked" onchange="setMobileUnMaskedValue()" value="{{old('mobile')}}" class="form-control input-air-primary" type="text">
                                    <input id="mobile" name="mobile" value="{{old('mobile')}}" type="hidden">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="timezone">TimeZone<span style="color:red;">*</span></label>
                                    <select id="timeZone"  class="form-control input-air-primary"  name="timezone"  aria-label="Default select example">
                                        @foreach($timezones as $key => $timezone)
                                        <option {{old('timezone','America/New_York') == $key? 'selected' : ''}} value="{{$key}}">{{$timezone}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="password">Password<span style="color:red;">*</span></label>
                                <input type="password" name="password" class="form-control">
                                <label for="" style="font-size:12px; color:gray;">password must contains capital and small letters , numbers and special character</label>
						        </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="plan_id">Subscription Plan</label>
                                    <select name="plan_id" id="plan_id"   class="form-control input-air-primary" >
                                        <option>Select A Subscription Plan</option>
                                        @foreach($plans as $plan)
                                            <option {{old('plan_id') == $plan->id? 'selected' : ''}} value="{{$plan->id}}">{{"$plan->name   , price is $plan->currency  $plan->monthly_price , monthly message is  $plan->monthly_message/month"}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="phone_number">Phone Number (AWS)</label>
                                    <select name="phone_number_id" id="phone_number"   class="form-control input-air-primary" >
                                        <option value="">Select A Phone Number</option>
                                        @foreach($phoneNumbers as $phone)
                                            <option {{old('phone_number_id') == $phone->id? 'selected' : ''}} value="{{$phone->id}}">{{$phone->phone_number}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="photos">Photos</label>
                                <input type="file" name="photos[]" accept="image/*" multiple class="form-control">
						        </div>
                            </div>
                        </div> --}}


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
<script src="https://unpkg.com/imask"></script>
<script>
    var element = document.getElementById('mobileMasked');
    var maskOptions = {
    mask: '+{1}(000)000-00-00'
    };
    var mask = IMask(element, maskOptions);
    function setMobileUnMaskedValue(){
        document.getElementById("mobile").value = "+" + mask.unmaskedValue;
    }
</script>
@endsection
