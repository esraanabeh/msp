@extends('layouts.simple.master')
@section('title', 'Base Inputs')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
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
                                <label class="col-sm-4 col-form-label">Title :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$textword->title}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Description :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$textword->description}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Monthly message limit :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$textword->monthly_message_limit}} </label>
                            </div>

                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Use Case :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$textword->usecase->name ?? ''}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Client :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$textword->client->name}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Created At :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$textword->created_at}} </label>
                            </div>




                        </div>
                    </div>

                    <div class="col-sm-12">


                        <div class="card">
                            <div class="card-header">
                            <h3>Contacts</h3>

                            </div>
                            <div class="card-body">
                                <div class="text-end mb-3">
                                    <a href="{{route('contacts.create')}}">
                                        <button class="btn btn-square btn-primary active" type="button">Create</button>
                                    </a>
                                    {{-- <a href="{{route('contacts.archive')}}">
                                        <button class="btn btn-square btn-success active" type="button">Archive</button>
                                    </a> --}}
                                </div>
                                <div class="table-responsive">
                                    <table class="display" id="contacts">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Is oupt out</th>
                                                <th>Mobile</th>
                                                <th>Subscription Arn</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Record</h5>
                                                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are You Sure to delete this record ?</p>
                                                </div>
                                                <form class="modal-footer" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary yes" type="button">Yes</button>
                                                </form>
                                            </div>
                                        </div>
                                       </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
</div>
@endsection

@section('script')
<script>
        $(document).ready(function () {
            $('#contacts').DataTable(
                {
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('textword.contacts.ajax',$textword->id) }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'is_opt_out', name: 'is_opt_out'},
                        {data: 'mobile', name: 'mobile'},
                        {data: 'subscription_arn', name: 'subscription_arn'},
						{data: 'action', name: 'action'},
                    ]
                }
            );
        });
		clickbutton = (e)=>{
			const modelId = e.getAttribute("data-id");
			var route = "{{route('contacts.destroy',':id')}}";
			route = route.replace(':id',modelId);
			$('#exampleModalCenter').find($('form')).attr('action',route);
		}
    </script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

@endsection

