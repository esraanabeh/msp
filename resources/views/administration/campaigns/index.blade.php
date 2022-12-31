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
                    <div style="margin-bottom: 14px;">
						<div class="form-group">
							<label>Client: </label>
							<select id="clients" name="clients_id" class="select2 form-control"
									data-placeholder="Select a client"
									style=display:inline-block;">
								<option value="">Select a Client</option>
								@foreach($clients as $client)
								<option value="{{$client->id}}">{{$client->name . "  (" . $client->email . ")"}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div style="margin-bottom: 14px;">
						<div class="form-group">
							<label>TextWord: </label>
							<select id="textwords" name="textwords_id" class="select2 form-control"
									data-placeholder="Select a textword"
									style=display:inline-block;">
								<option value="">Select a Textword</option>
								@foreach($textwords as $textword)
								<option value="{{$textword->id}}">{{$textword->title}}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div style="margin-bottom: 14px;">
						<div class="form-group">
							<label>Status: </label>
							<select id="status" name="status" class="select2 form-control"
									data-placeholder="Select a status"
									style="display:inline-block;">
								<option value="">Select a Status</option>
								<option value="pending">pending</option>
								<option value="in-progress">in-progress</option>
                                <option value="sent">sent</option>
                                <option value="failed">failed</option>
							</select>
						</div>
					</div>
					<div class="table-responsive">
						<table class="display" id="campaigns">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
                                    <th>Client</th>
                                    <th>Textword</th>
                                    <th>Status</th>
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

            $('#clients').select2();
			$('#textwords').select2();
            $('#status').select2();
            var client_id;
            var textword_id;
			var status;
            var table = $('#campaigns').DataTable(
                {
                    "processing": true,
                    "serverSide": true,
                    "ajax":{
                        url : "{{ route('campaigns.index.ajax') }}",
                        data: function (data)
                        {
                           data.client_id = client_id;
                           data.textword_id = textword_id;
                           data.status = status
                        }
                    } ,
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data:'client', name: 'client'},
                        {data: 'textword', name: 'textword'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action'}
                    ],
                }
            );

            $('#clients').change(function() {
			client_id = filterClick("clients");
			table.draw();
			});
			$('#textwords').change(function() {
			textword_id = filterClick("textwords");
			table.draw();
			});

			$('#status').change(function() {
			status = filterClick("status");
			table.draw();
			});
			function filterClick(itemId)
			{
			return document.getElementById(itemId).value;
			}

        });
</script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

@endsection
