@extends('layouts.simple.master')
@section('title', 'Basic DataTables')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Administrations</h3>
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
						<a href="{{route('clients.create')}}">
							<button class="btn btn-square btn-primary active" type="button">Create</button>
						</a>
						<a href="{{route('clients.archive')}}">
							<button class="btn btn-square btn-success active" type="button">Archive</button>
						</a>
					</div>
					<div class="table-responsive">
						<table class="display" id="clients">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Subscription</th>
                                    <th>Current Credit</th>
                                    <th>Rollover Credit</th>
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
            $('#clients').DataTable(
                {
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('clients.index.ajax') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'status', name: 'status'},
                        {data: 'subscription', name: 'subscription'},
                        {data:'total_credit', name:'total_credit'},
                        {data: 'roleover_credit', name:'roleover_credit'},
						{data: 'action', name: 'action'},
                    ]
                }
            );
        });
        function login(id){

            $.ajax({
                url:  window.location.origin+ "/admin/clients/"+id+"/impersonate",
                success:function(data)
                {

                    window.localStorage.setItem('token',data['token'])
                    window.localStorage.setItem('clientId',data['id'])
                    window.localStorage.setItem('logged_as',data['name'])
                    window.localStorage.setItem('expire_at',data['expire_at'])
                    window.localStorage.setItem('admin',data['admin'])
                    window.location =  window.location.origin
                }
                });
            }
		clickbutton = (e)=>{
			const modelId = e.getAttribute("data-id");
			var route = "{{route('clients.destroy',':id')}}";
			route = route.replace(':id',modelId);
			$('#exampleModalCenter').find($('form')).attr('action',route);
		}


    </script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

@endsection
