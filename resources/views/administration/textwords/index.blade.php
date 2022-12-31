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
						<a href="{{route('textwords.create')}}">
							<button class="btn btn-square btn-primary active" type="button">Create</button>
						</a>
						{{-- <a href="{{route('textwords.archive')}}">
							<button class="btn btn-square btn-success active" type="button">Archive</button>
						</a> --}}
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
					<div class="table-responsive">
						<table class="display" id="textwords">
							<thead>
								<tr>
									<th>ID</th>
									<th>Title</th>
                                    <th>Description</th>
                                    <th>Client</th>
                                    <th>Created At</th>
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
			var client_id;
            var textwordTable = $('#textwords').DataTable(
                {
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
						'url':"{{ route('textwords.index.ajax') }}",
                        data: function(data)
                        {
                            data.client_id = client_id;
                        },
					},
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'title', name: 'title'},
                        {data: 'description', name: 'description'},
                        {data: 'client', name: 'client'},
                        {data: 'created_at', name: 'created_at'},
						{data: 'action', name: 'action'},
                    ]
                }
            );

			$('#clients').change(function() {

				client_id = filterClick();
				textwordTable.draw();
			});

			function filterClick()
			{
				var clientId = document.getElementById("clients").value;
				return clientId;
			}
        });

		

		clickbutton = (e)=>{
			const modelId = e.getAttribute("data-id");
			var route = "{{route('textwords.destroy',':id')}}";
			route = route.replace(':id',modelId);
			$('#exampleModalCenter').find($('form')).attr('action',route);
		}
    </script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
@endsection
