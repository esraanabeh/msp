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
                                <label class="col-sm-4 col-form-label">Name :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$client->name}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Email :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$client->email}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Mobile :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$client->mobile}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Timezone :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$client->timezone}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Status :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$client->status}} </label>
                            </div>
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Phone Number (AWS) :</label>
                                <label
                                    class="col-sm-8 col-form-label">{{$client->phoneNumber->phone->phone_number ?? ''}} </label>
                            </div>
                            @if ($client->images)
                            <div class=" row">
                                <label class="col-sm-4 col-form-label">Photos :</label>
                                <div class="gallery my-gallery card-body row" itemscope="" data-pswp-uid="1">
                                    @foreach($client->images as  $media)

                                    <figure class="col-xl-3 col-md-4 col-6" itemprop="associatedMedia" itemscope="">
                                       <a  href="{{$media->getUrl('origin')}}" target="blank" itemprop="contentUrl"  data-bs-original-title="" title=""><img class="img-thumbnail"  src="{{$media->getUrl('card')}}" style="width: 150px!important;height: 100px!important;"  itemprop="thumbnail"></a>

                                    </figure>


                                    @endforeach
                                </div>
                            </div>
                            @endif



                        </div>

                    </div>
    </div>

    <div class="row">
        <h4>Textwords</h4>

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
    </div>
</div>
                    <div class="row">
                        <h4>Contacts</h4>
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-end  m-3">
                                    <a href="{{route('contacts.create')}}">
                                        <button class="btn btn-square btn-primary active" type="button">Create</button>
                                    </a>

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

            <div class="row">
                <h4>subscriptions</h4>
                    <div class="card-body">
                        <div class="col-12 card card-primary">
                            <div class="text-end  m-3">
                                @if( !$client->stripe_id || !$client->subscriptions()->active()->get())
                                <a href="{{route('clients.subscribe.create')}}">
                                    <button class="btn btn-square btn-primary active" type="button">Create Subscription</button>
                                </a>
                                @else
                                <a href="{{route('clients.subscribe-swap.create',$client->id)}}">
                                    <button class="btn btn-square btn-primary active" type="button">Change Subscription</button>
                                </a>
                                @endif
                            </div>
                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th>Id</th>

                                    <th>Amount</th>

                                    <th>Interval</th>

                                    <th>End Date</th>

                                    <th>Canceled At</th>

                                    <th>Payment Method</th>

                                    <th>Rollover Credit</th>

                                    <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                @if(!empty($subscriptions))

                                    @foreach($subscriptions as $key=> $subscription)
                                    <tr>
                                        <td>{{  ++$key }}</td>
                                            <td>{{$subscription->price .' '.$subscription->currency}}</td>

                                            <td>{{ $subscription->interval }}</td>

                                            <td>{{$subscription->period_end }}</td>
                                            <td>{{ $subscription->cancel_at  }}</td>

                                            <td>{{ $subscription->card_no }}</td>

                                            <td> {{$subscription->rollover_messages}} <br>
                                                @if(!$subscription->cancel_at && $subscription->status == 'active' && $loop->first)
                                                <a  href='{{route("clients.rollover.update",$client->id)}}' title = "Rollover Managment"><i class="fa fa-edit"></i></a>
                                                @endif
                                             </td>

                                            <td>
                                                @if(!$subscription->cancel_at && $subscription->status == 'active' && $loop->first)
                                                <button onClick="clickbutton(this)" class="btn btn-danger delete mx-1" type="button" data-id="{{$client->id}}" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Cancel Subscription</button>
                                                @endif
                                                @if($subscription->invoice_id)
                                                <a href='{{route("clients.download-invoice",[$client->id,$subscription->invoice_id])}}' class="btn btn-primary mx-1">Download Invoice</button>
                                                @endif
                                            </td>

                                        </tr>

                                    @endforeach

                                @endif

                            </tbody>

                        </table>
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Delete Record</h5>
										<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<p>Are You Sure want to cancel subscription ?</p>
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
@endsection

@section('script')
<script>
    $(document).ready(function () {

    $('#textwords').DataTable(
            {
                "processing": true,
                "serverSide": true,
                "ajax":{
                    url: "{{ route('textwords.index.ajax') }}",
                    data: function (d) {
                        d.client_id = "{{$client->id}}";
                    }
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


    clickbutton = (e)=>{
        const modelId = e.getAttribute("data-id");
        var route = "{{route('clients.subscribe.cancel',':id')}}";
        route = route.replace(':id',modelId);
        $('#exampleModalCenter').find($('form')).attr('action',route);
    }

        $('#contacts').DataTable(
            {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('client.contacts.ajax',$client->id) }}",
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

    </script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

@endsection

