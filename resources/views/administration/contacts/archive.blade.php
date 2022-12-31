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
					<div class="table-responsive">
						<table class="display" id="contacts">
							<thead>
								<tr>
                                    <th>ID</th>
									<th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Client</th>
                                    <th>Textwords</th>
									<th>Action</th>
								</tr>
							</thead>
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
            $('#contacts').DataTable(
                {
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('contacts.archive') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'mobile', name: 'mobile'},
                        {data: 'client', name: 'client'},
                        {data: 'textwords', name: 'textwords'},
						{data: 'action', name: 'action'},
                    ]
                }
            );
        });
    </script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

@endsection
