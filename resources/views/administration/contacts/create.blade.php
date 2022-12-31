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
                <form id="createForm" class="form theme-form" action="{{route("contacts.store")}}" med method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="first_name">First Name<span style="color:red;">*</span></label>
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
                                    <input onchange="setMobileUnMaskedValue()" id="mobileMasked" value="{{old('mobile')}}" class="form-control input-air-primary" type="text">
                                    <input id="mobile" name="mobile" value="{{old('mobile')}}" type="hidden">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="city">City<span style="color:red;">*</span></label>
                                    <input name="city" value="{{old('city')}}" class="form-control input-air-primary" id="city" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="state">State<span style="color:red;">*</span></label>
                                    <input name="state" value="{{old('state')}}" class="form-control input-air-primary" id="state" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="zip_code">Zip Code<span style="color:red;">*</span></label>
                                    <input name="zip_code" value="{{old('zip_code')}}" class="form-control input-air-primary" id="zip_code" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="client_id">Client<span style="color:red;">*</span></label>
                                    <select name="client_id"  id="client_id" class="form-control input-air-primary">
                                        <option>Please Select Client</option>

                                        @foreach($clients as $client)
                                            <option {{old('client_id') == $client->id ? 'selected' : ''}} value="{{$client->id}}">{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="textword_id">Related Textword<span style="color:red;">*</span></label>
                                    <option>Please Select Textword</option>

                                    <select name="textword_id" id="textword_id"   class="form-control input-air-primary" >
                                        {{-- @foreach($textwords as $textword)
                                            <option {{in_array($textword->id,old('textword_id',[])) ? 'selected' : ''}} value="{{$textword->id}}">{{$textword->title}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="dob">Date of birth<span style="color:red;">*</span></label>
                                    <input name="dob" value="{{old('dob')}}" class="form-control input-air-primary" id="dob" type="date">
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <input class="btn btn-light" onclick="document.location='/admin/contacts'" type='button' value="Cancel">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

<script>
    var element = document.getElementById('mobileMasked');
    var maskOptions = {
    mask: '+{1}(000)000-00-00'
    };
    var mask = IMask(element, maskOptions);
    function setMobileUnMaskedValue(){
        document.getElementById("mobile").value = "+" + mask.unmaskedValue;  
    }
               $("#textword_id").select2({
                tags: false,
                placeholder: "Select Textword"
            });
            $('#client_id').change(function () {
                $('#textword_id').val(null).trigger('change');
                $("#textword_id").select2({
                    ajax: {
                        url: "{{route('listTextwords')}}",
                        data: {client_id: $('#client_id').val()},
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.title,
                                        id: item.id
                                    }
                                })
                            };
                        },
                    },

                });

            });
    </script>
@endsection
