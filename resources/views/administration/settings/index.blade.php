@extends('layouts.simple.master')
@section('title', 'Basic DataTables')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
@endsection

@section('style')
@endsection





@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Zero Configuration  Starts-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">

					<div class="table-responsive">
						<table class="display" id="settings">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
                                    <th>Value</th>
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
            $('#settings').DataTable(
                {
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('settings.index.ajax') }}",
                    "oLanguage": {
                        "sInfoEmpty": '{{ __('dashboard.no_entries_to_show')}}',
                        "sInfo": '{{ __('dashboard.entries_to_show')}}',
                        "sEmptyTable": '{{ __('dashboard.no_data')}}',
                        "sSearch": '{{ __('dashboard.search')}}',
                        "sLengthMenu": '{{ __('dashboard.length_Menu')}}',
                        "sProcessing": '{{ __('dashboard.processing')}}',

                        "oPaginate": {
                            "sNext": '{{ __('dashboard.next') }}',
                            "sPrevious": '{{ __('dashboard.previous') }}'
                            }
                        },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'value', name: 'value'},
						{data: 'action', name: 'action'},
                    ]
                }
            );
        });

    </script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

@endsection
