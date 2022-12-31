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
					<div class="text-end mb-3">
						<a href="{{route('users.create')}}">
							<button class="btn btn-square btn-primary active" type="button">Create</button>
						</a>
						<!-- <a href="{{route('users.archive')}}">
							<button class="btn btn-square btn-success active" type="button">Archive</button>
						</a> -->
					</div>
					<div class="table-responsive">
						<table class="display" id="users">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
                                    <th>Email</th>
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
		<!-- Zero Configuration  Ends-->
		<!-- Feature Unable /Disable Order Starts-->
	</div>
</div>
@endsection

@section('script')
<script>
        $(document).ready(function () {
            $('#users').DataTable(
                {
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('users.index.ajax') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
						{data: 'action', name: 'action'},
                    ]
                }
            );
        });
		clickbutton = (e)=>{
			const modelId = e.getAttribute("data-id");
			var route = "{{route('users.destroy',':id')}}";
			route = route.replace(':id',modelId);
			$('#exampleModalCenter').find($('form')).attr('action',route);
		}
    </script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

@endsection
