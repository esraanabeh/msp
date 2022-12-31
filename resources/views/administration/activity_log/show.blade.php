@extends('layouts.simple.master')
@section('title', 'Base Inputs')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/js/json-viewer/jquery.json-viewer.css')}}">

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
                    <!-- form start -->
                    <div class="card-body">
                        <div class=" row">
                            <label class="col-sm-4 col-form-label">Description</label>
                            <label class="col-sm-8 col-form-label">{{$activity->description}} </label>
                        </div>
                        <div class=" row">
                            <label class="col-sm-4 col-form-label">Properties</label>
                            <pre  class="col-sm-8 col-form-label" readonly id="json-display"></pre>
                        </div>
                        <div class=" row">
                            <label class="col-sm-4 col-form-label">Model ID</label>
                            <label class="col-sm-8 col-form-label">{{$activity->subject_id}} </label>
                        </div>
                        <div class=" row">
                            <label class="col-sm-4 col-form-label">Model Type</label>
                            <label
                                class="col-sm-8 col-form-label">{{substr($activity->subject_type, strrpos($activity->subject_type, '/') + 11)}} </label>
                        </div>
                        <div class=" row">
                            <label class="col-sm-4 col-form-label">Causer Name :</label>
                            <label
                                class="col-sm-8 col-form-label">{{isset($activity->causer)?$activity->causer->name:'-'}} </label>
                        </div>

                        <div class=" row">
                            <label class="col-sm-4 col-form-label"> Causer Type :</label>
                            <label
                                class="col-sm-8 col-form-label">{{isset($activity->causer_type)?substr($activity->causer_type, strrpos($activity->causer_type, '/') + 11):'-'}} </label>
                        </div>
                        @if($admin)
                        <div class=" row">
                            <label class="col-sm-4 col-form-label"> Admin Name :</label>
                            <label
                                class="col-sm-8 col-form-label">{{ $admin->name}} </label>
                        </div>
                        @endif
                        <!-- <div class=" row">
                            <label class="col-sm-4 col-form-label"> Causer Attributes :</label>
                            <label
                                class="col-sm-8 col-form-label">
                                <code>
                                    @json ($activity->properties->toArray()['attributes'])<br>
                                    @isset($activity->properties->toArray()['old'])
                                    Old Data: @json ($activity->properties->toArray()['old'])@endisset
                                </code>
                            </label>
                        </div> -->
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
@stop

@section('script')



<script src="{{asset('assets/js/json-viewer/jquery.json-viewer.js')}}"></script>
<script>

$('#json-display').jsonViewer(@json($activity->properties));

</script>
@endsection
