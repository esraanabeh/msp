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
                <form class="form theme-form" action="{{route("plans.update",$plan->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input name="name" class="form-control input-air-primary" value="{{old('name',$plan->name)}}" id="name" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="price">Price</label>
                                <input type="number" disabled value="{{old('price',$plan->monthly_price)}}" name="price" class="form-control">
						        </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="currency">Currency</label>
                                <select id="currency" disabled name="currency" class="form-control">
                                    <option>Select currency</option>
                                    @foreach ($currencies as $key=> $currency)
                                        <option {{old('currency',$plan->currency) == $key ? 'selected' : ''}} value="{{$key}}">{{$currency}}</option>
                                    @endforeach

                                </select>


						        </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="monthly_message">Monthly message<span style="color:red;">*</span></label>
                                <input type="number" disabled value="{{old('monthly_message',$plan->monthly_message)}}" name="monthly_message" class="form-control">
						        </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="max_textword">Textwords<span style="color:red;">*</span></label>
                                <input type="number" value="{{old('max_textword',$plan->max_textword)}}" name="max_textword" class="form-control">
						        </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                    <label for="max_contact">Max Contacts<span style="color:red;">*</span></label>
                                    <br>
                                    <label><span>(is unlimited contacts ?)</span></label>
                                    <input name="unlimited_contact" id="unlimited_contacts" type="checkbox"  class="form-check-input m-2" value="true" {{old('unlimited_contact') == 'true'  || $plan->max_contact == null ? 'checked' :''}}/>
                                <input type="number" id="contact_value" value="{{old('max_contact',$plan->max_contact)}}" {{old('max_contact',$plan->max_contact) == 'null' ? 'placeholder="unlimited contacts"  disabled' : ''}} name="max_contact" class="form-control">
						        </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="allow_rollover">Allow rollover</label>
                                <input name="allow_rollover" type="checkbox"  class="form-check-input m-2" {{old('allow_rollover',$plan->allow_rollover)  ? 'checked' :''}}/>
						        </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                <label for="status">Status<span style="color:red;">*</span></label>
                                <input name="status" type="checkbox"  class="form-check-input m-2"  {{old('status',$plan->status) ? 'checked' :''}}/>
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
<script>
    $('#unlimited_contacts').change(function(){
        unlimited()
    })
    unlimited()
    function unlimited(){
        if($('#unlimited_contacts').is(':checked') == true){
            $('#contact_value').prop('disabled',true)
            $('#contact_value').prop('placeholder','unlimited contacts')
        }else{
            $('#contact_value').prop('disabled',false)
            $('#contact_value').prop('placeholder','')
        }
    }

</script>
@endsection
