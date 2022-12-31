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
                    <h4> Available phone  number {{$available}}</h4>
					<div class="text-end mb-3">
						<a href="{{route('phone-number.sync')}}">
							<button class="btn btn-square btn-primary active" type="button">Sync</button>
						</a>

					</div>
					<div class="table-responsive">
						<table class="display" id="phone_numbers">
							<thead>
								<tr>
									<th>ID</th>
									<th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Client</th>
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
            $('#phone_numbers').DataTable(
                {
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('phone-number.index') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'phone_number', name: 'phone_number'},
                        {data: 'status', name: 'status'},
                        {data: 'type', name: 'type'},
                        {data: 'client', name: 'client'},
                    ]
                }
            );
        });

    </script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

@endsection
