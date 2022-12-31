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
            <div class="col-sm-12">
                <div class="card">
                    <form class="form theme-form" action="{{route("settings.update")}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @foreach ($settings as $setting)
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            @if(!$loop->first)
                                                <hr class="mt-4 mb-4">
                                            @endif
                                            <label class="my-1 mb-2"
                                                   for="{{$setting->key_name}}">{{$setting->name}}</label>

                                            @if($setting->key_value == 'logo')


                                                <div class="row">
                                                    <div class="col">
                                                        <div class="mb-2">
                                                            <label for="photos">Photo<span
                                                                    style="color:red;">*</span></label>
                                                            <input type="file" name="{{$setting->key_name}}" accept="image/*"
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="gallery my-gallery card-body row" itemscope=""
                                                     data-pswp-uid="1">
                                                    @if($setting->image)
                                                        <figure class="col-xl-3 col-md-4 col-6"
                                                                itemprop="associatedMedia" itemscope="">
                                                            <a href="{{$setting->image->getUrl('origin')}}"
                                                               target="blank" itemprop="contentUrl"
                                                               data-bs-original-title="" title=""><img
                                                                    class="img-thumbnail"
                                                                    src="{{$setting->image->getUrl('card')}}"
                                                                    style="width: 150px!important;height: 100px!important;"
                                                                    itemprop="thumbnail"></a>

                                                            <br>
                                                            <a href="{{route('administrations.destroy-media',$setting->image->id)}}">
                                                                <i class="icofont icofont-ui-close  text-danger "></i></a>
                                                        </figure>
                                                    @endif
                                                </div>

                                            @elseif (gettype($setting->key_value) == 'string')

                                                <input name="{{$setting->key_name}}" value="{{$setting->key_value}}"
                                                       class="form-control input-air-primary" {{$setting->key_name == 'terms_condition_shorten_url' ? 'disabled' : ''}}  id="{{$setting->key_name}}"
                                                       type="{{str_contains($setting->key_name,'url') ? 'url' : 'text'}}">
                                            @elseif (str_contains($setting->key,'protien') || ($setting->key =='meal_type_with_limit'))
                                                <div class="row">

                                                    @foreach ($setting->key_value as $key =>$value )
                                                        <div class="form-group col-md-4">
                                                            <label for="{{$key}}">{{$key}}</label>
                                                            <input type="text" name="{{$setting->key_name}}[{{$key}}]"
                                                                   class="form-control" value="{{$value}}"
                                                                   placeholder="{{$key}}">
                                                        </div>
                                                    @endforeach
                                                </div>

                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">submit</button>
                        <input class="btn btn-light" onclick="document.location='/admin/settings'" type='button' value="cancel">
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

    </script>
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
@endsection
