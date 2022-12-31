@extends('layouts.simple.master')
@section('title', 'Base Inputs')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection





@section('content')
<div class="container-fluid">
    <div class="row">
                    <div class="col-12 card card-primary">
                        <div class="card-body">
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Name :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$setting->name}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Value :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$setting->key_value}} </label>
                            </div>

                            @if ($setting->image)
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Photos :</label>
                                <div class="gallery my-gallery card-body row" itemscope="" data-pswp-uid="1">

                                    <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope="">
                                       <a  href="{{$setting->image->getUrl('origin')}}" target="blank" itemprop="contentUrl"  data-bs-original-title="" title=""><img class="img-thumbnail"  src="{{$admin->image->getUrl('card')}}" style="width: 150px!important;height: 100px!important;"  itemprop="thumbnail"></a>

                                    </figure>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
            </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
