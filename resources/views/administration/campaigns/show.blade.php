@extends('layouts.simple.master')
@section('title', 'Base Inputs')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<style>
td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}    
</style>
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
                    <div class=" row">
                        <label class="col-sm-4 col-form-label">Name :</label>
                        <label
                            class="col-sm-8 col-form-label">{{$campaign->name}} </label>
                    </div>
                    <div class=" row">
                        <label class="col-sm-4 col-form-label">Message :</label>
                        <label
                            class="col-sm-8 col-form-label">{{$campaign->message}} </label>
                    </div>
                    <div class=" row">
                        <label class="col-sm-4 col-form-label">Textword :</label>
                        <label
                            class="col-sm-8 col-form-label">{{$campaign->textword->title}} </label>
                    </div>
                    <div class=" row">
                        <label class="col-sm-4 col-form-label">Status :</label>
                        <label
                            class="col-sm-8 col-form-label">{{$campaign->status}} </label>
                    </div>
                    @if($campaign->published_at)
                    <div class=" row">
                        <label class="col-sm-4 col-form-label">Published At :</label>
                        <label
                            class="col-sm-8 col-form-label">{{$campaign->published_at}} </label>
                    </div>
                    @elseif($campaign->send_day && $campaign->send_time && $campaign->repeated_at)
                    <div class=" row">
                        <label class="col-sm-4 col-form-label">Send Day :</label>
                        <label
                            class="col-sm-8 col-form-label">{{$campaign->send_day ?? ''}} </label>
                    </div>
                    <div class=" row">
                        <label class="col-sm-4 col-form-label">Send Time :</label>
                        <label
                            class="col-sm-8 col-form-label">{{$campaign->send_time ?? ''}} </label>
                    </div>
                    <div class=" row">
                        <label class="col-sm-4 col-form-label">Repeated At :</label>
                        <label
                            class="col-sm-8 col-form-label">{{$campaign->repeated_at ?? ''}} </label>
                    </div>
                    @else

                    @endif
                </div>
            </div>
            
            <div class="col-12 card card-primary">
                <h4 style="padding:20px;">Campaign History</h4>
                <div class="table-responsive">
                    <table class="display" id="campaignsHistory">
                        <thead>
                            <tr>
                                <th>Count Of Contacts</th>
                                <th>Clicks</th>
                                <th>Success</th>
                                <th>Fails</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
    </div>
</div>
@endsection

@section('script')
<script>
        $(document).ready(function () {
            $('#campaignsHistory').DataTable(
                {
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('campaigns-history.ajax',$campaign->id) }}",
                    columns: [
                        {data: 'count_of_contacts', name: 'count_of_contacts'},
                        {data: 'clicks', name: 'clicks'},
                        {data: 'success', name: 'success'},
                        {data: 'fails', name: 'fails'}
                    ]
                }
            );
        });
</script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

@endsection

