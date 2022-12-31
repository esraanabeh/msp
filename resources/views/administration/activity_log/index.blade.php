@extends('layouts.simple.master')
@section('title', 'Basic DataTables')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Tables</li>
<li class="breadcrumb-item active">Basic DataTables</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="activityLog" class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                    <th>causer_Email</th>
                                    <th>time</th>
                                    <th>option</th>

                                </tr>
                            </thead>
                        </table>
                    </div>
                    {{-- <div class="table-responsive">
                        <table id="activityLog" class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Causer</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Activities as $activity)
                                <tr>
                                    <td>{{$activity->log_name}}</td>
                                    <td>{{$activity->description}}</td>
                                    <td>{{$activity->causer_id}}</td>
                                    <td>{{$activity->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#activityLog').DataTable(
            {

                "processing": true,
                "serverSide": true,
                "ajax": "{!!route('activity.index.ajax')!!}",
                columns: [
                    {data: 'Name', name: 'Name'},
                    {data: 'action', name: 'action'},
                    {data: 'causer', name: 'causer'},
                    {data: 'time', name: 'time'},
                    {data: 'option', name: 'option'},

                ]
            }
        );
    });
</script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
@endsection
