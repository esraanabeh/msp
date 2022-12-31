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
                <form id="createForm" class="form theme-form" action="{{route("clients.update",$client->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="first_name">First Name<span style="color:red;">*</span></label>
                                    <input name="first_name" value="{{$client->first_name}}" class="form-control input-air-primary" id="first_name" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="last_name">Last Name<span style="color:red;">*</span></label>
                                    <input name="last_name" value="{{$client->last_name}}" class="form-control input-air-primary" id="last_name" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="email">Email<span style="color:red;">*</span></label>
                                <input type="text" value="{{$client->email}}" name="email" class="form-control">
						        </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="timezone">TimeZone<span style="color:red;">*</span></label>
                                    <select id="timeZone"  class="form-control input-air-primary"  name="timezone"  aria-label="Default select example">
                                        @foreach($timezones as $key => $timezone)
                                        <option {{old('timezone',$client->timezone) == $key? 'selected' : ''}} value="{{$key}}">{{$timezone}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                    <div class="col-form-label">Status<span style="color:red;">*</span></div>
                                    <select name="status" class="js-example-placeholder-multiple col-sm-12">
                                    <option value="active" {{old('status',$client->status) == 'active' ?'selected':''}}>Active</option>
                                    <option value="inactive" {{old('status',$client->status) == 'inactive' ?'selected':''}} >Inactive</option>
                                    </select>
						        </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="mobile">Mobile<span style="color:red;">*</span></label>
                                    <input onchange="setMobileUnMaskedValue()" id="mobileMasked" value="{{$client->mobile}}" class="form-control input-air-primary" type="text">
                                    <input id="mobile" name="mobile" value="{{$client->mobile}}" type="hidden">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control">
                                <label for="" style="font-size:12px; color:gray;">password must contains capital and small letters , numbers and special character</label>
						        </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="aws_phone_number_id">Phone Number (AWS)</label>
                                    <select name="aws_phone_number_id" id="aws_phone_number_id"   class="form-control input-air-primary" >
                                        <option  value="">Select A Phone Number</option>
                                        @if($client->phoneNumberAws)
                                            <option selected value="{{$client->phoneNumberAws->phone_number_id}}">{{$client->phoneNumberAws->phone->phone_number}} </option>
                                        @endif
                                        @foreach($phoneNumbers->where('provider','AWS') as $phone)
                                            <option {{old('aws_phone_number_id',$client->phone_number_id) == $phone->id? 'selected' : ''}} value="{{$phone->id}}">{{$phone->phone_number}} </option>
                                        @endforeach
                                    </select>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" name="is_default" type="radio" {{$client->phoneNumberAws && $client->phoneNumberAws->is_default ? 'checked':'' }} value="AWS" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                          Default (AWS)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="twilio_phone_number_id">Phone Number (Twilo)</label>
                                    <select name="twilio_phone_number_id" id="twilio_phone_number_id"   class="form-control input-air-primary" >
                                        <option  value="">Select A Phone Number</option>
                                        @if($client->phoneNumberTwilio)
                                            <option selected value="{{$client->phoneNumberTwilio->phone_number_id}}">{{$client->phoneNumberTwilio->phone->phone_number}} </option>
                                        @endif
                                        @foreach($phoneNumbers->where('provider','Twilio') as $phone)
                                            <option {{old('twilio_phone_number_id',$client->phone_number_id) == $phone->id? 'selected' : ''}} value="{{$phone->id}}">{{$phone->phone_number}} </option>
                                        @endforeach
                                    </select>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" name="is_default" type="radio"    value="Twilio" id="flexCheckDefaultTwilo" {{$client->phoneNumberTwilio && $client->phoneNumberTwilio->is_default ? 'checked':'' }}>
                                        <label class="form-check-label" for="flexCheckDefaultTwilo">
                                          Default (Twilio)
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="photos">Photos</label>
                                <input type="file" name="photos[]" accept="image/*" multiple class="form-control">
						        </div>
                            </div>
                        </div> --}}
                        {{-- <br>
                        <div class="gallery my-gallery card-body row" itemscope="" data-pswp-uid="1">
                            @foreach($client->images as  $media)

                            <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope="">
                               <a  href="{{$media->getUrl('origin')}}" target="blank" itemprop="contentUrl"  data-bs-original-title="" title=""><img class="img-thumbnail"  src="{{$media->getUrl('card')}}" style="width: 150px!important;height: 100px!important;"  itemprop="thumbnail"></a>

                                <br>
                                <a href="{{route('clients.destroy-media',$media->id)}}">
                                    <i class="icofont icofont-ui-close  text-danger "></i></a>
                            </figure>


                            @endforeach
                        </div> --}}


                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <input class="btn btn-light" onclick="document.location='/client/clients'" type='button' value="Cancel">
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
