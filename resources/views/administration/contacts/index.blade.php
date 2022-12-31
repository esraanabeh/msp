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
						<a href="{{route('contacts.create')}}">
							<button class="btn btn-square btn-primary active" type="button">Create</button>
						</a>
						<a href="{{route('contacts.archive')}}">
							<button class="btn btn-square btn-success active" type="button">Archive</button>
						</a>
					</div>
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
									style=display:inline-block;">
								<option value="">Select a Status</option>
								<option value="active">Active</option>
								<option value="inactive">Inactive</option>
							</select>
						</div>
					</div>
					<div class="table-responsive">
						<table class="display" id="contacts">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
									<th>Status</th>
                                    {{-- <th>Client</th> --}}
                                    {{-- <th>Textwords</th> --}}
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
			$('#clients').select2();
			$('#textwords').select2();
			$('#status').select2();
			var client_id;
			var textword_id;
			var status;
			var contactsTable =  $('#contacts').DataTable(
                {
                    "processing": true,
                    "serverSide": true,
					"ajax": {
						'url':"{{ route('contacts.index.ajax') }}",
                        data: function(data)
                        {
                            data.client_id = client_id;
							data.textword_id = textword_id;
							data.status = status;
                        },
					},
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'mobile', name: 'mobile'},
						{data: 'status', name: 'status'},
                        // {data: 'client', name: 'client'},
                        // {data: 'textwords', name: 'textwords'},
						{data: 'action', name: 'action'},
                    ]
                }
            );

			$('#clients').change(function() {
			client_id = filterClick("clients");
			contactsTable.draw();
			});


			$('#textwords').change(function() {
			textword_id = filterClick("textwords");
			contactsTable.draw();
			});

		

			$('#status').change(function() {
			status = filterClick("status");
			contactsTable.draw();
			});

			function filterClick(itemId)
			{
			return document.getElementById(itemId).value;
			}
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
