@extends('layouts.simple.master')
@section('title', 'Basic DataTables')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
@endsection

@section('style')


@endsection

@section('breadcrumb-title')
<h3>Users</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Tables</li>
<li class="breadcrumb-item active">Basic DataTables</li>
@endsection

@section('content')
<div class="container-fluid">

	<div class="row">
		<!-- Zero Configuration  Starts-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table class="display" id="feedbacks">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
                                    <th>Mobile</th>
                                    <th>Reason</th>
									<th>Message</th>
                                    <th>Organization</th>
                                    <th>Seen</th>
                                    <th>Action</th>
								</tr>
							</thead>
                            <tbody>
                                @foreach ($feedbacks as $feedback )
                                <tr>
									<td>{{$feedback->id}}</td>
                                    <td>{{$feedback->first_name ." ". $feedback->last_name }}</td>
                                    <td>{{$feedback->mobile}}</td>
                                    <td>{{$feedback->reason}}</td>
                                    <td>   {{ Str::limit($feedback->message, 20) }}</td>
                                    <td>{{$feedback->organization}}</td>
                                    <td>@if ($feedback->is_seen ==1)
										<svg xmlns="http://www.w3.org/2000/svg" color="blue" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
									@endif</td>
                                    <td><a class="btn btn-warning" href="{{route('users.contact-us-read',$feedback->id)}}"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
										<a class="btn btn-danger" href="{{route('users.contact-us-delete',$feedback->id)}}" onClick="return confirm('Delete this record??')"  data-toggle="modal" data-target="#exampleModal"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
									</td>
                                </tr>
                                @endforeach

                            </tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
		<!-- Zero Configuration  Ends-->
		<!-- Feature Unable /Disable Order Starts-->
	</div>
</div>


@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#feedbacks').DataTable({});
    });


</script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

@endsection
