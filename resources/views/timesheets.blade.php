@extends('layouts.master')

@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection

@section('content')
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<!-- <input class="date" id="date" type="date"> -->
						<div class="row">
							<div class="col-sm-6">
								<div class="row">
									<input id="date" class="datepicker" style="margin-left: 16px">
									<button class="btn btn-success" style="display:none" id="accept">Accept</button>
									<button class="btn btn-danger" style="display:none" id="reject">Reject</button>
								</div>
							</div>
							<div class="col-sm-1">
							</div>
							<div class="col-sm-5">
								<div class="row">
									<span class="badge"></span>
									<select name="search" id="search">
										<option>Select User</option>
										@foreach($users as $user)
											<option value="{{$user->id}}" usertype="{{$user->user_type}}" rate="{{$user->rate}}">{{$user->firstname}} ({{ $user->email }})</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>

					<input type="hidden" name="date_format" id="date_format" value="{{ Auth::user()->date_format }}">
					<div class="box-body">
						<input type="hidden" id="user_id" value="{{ Auth::id() }}">
						<!-- <input type="hidden" id="user_type" value="teacher"> -->

						<table id="timetable" class="table table-bordered table-striped responsive">
							<thead>
								<tr>
									<th width="30%">
										Subject/Class
									</th>
									<th width="10%">
										<p>Monday</p>
										<p id="0"></p>
									</th>
									<th width="10%">
										<p>Tuesday</p>
										<p id="1"></p>
									</th>
									<th width="10%">
										<p>Wednesday</p>
										<p id="2"></p>
									</th>
									<th width="10%">
										<p>Thursday</p>
										<p id="3"></p>
									</th>
									<th width="10%">
										<p>Friday</p>
										<p id="4"></p>
									</th>
									<th width="10%">
										<p>Saturday</p>
										<p id="5"></p>
									</th>
									<th width="10%">
										<p>Sunday</p>
										<p id="6"></p>
									</th>
								</tr>
							</thead>
							<tbody class="timetable">
							</tbody>
							
						</table>
						<table style="display:none">
							<tr class="editable" width="25%" task_id="" style="display:none;">
								<td class="task"></td>
								<td width="10%" dow="0" date="">
								</td>
								<td width="10%" dow="1" date="">
								</td>
								<td width="10%" dow="2" date="">
								</td>
								<td width="10%" dow="3" date="">
								</td>
								<td width="10%" dow="4" date="">
								</td>
								<td width="10%" dow="5" date="">
								</td>
								<td width="10%" dow="6" date="">
								</td>
							</tr>
						</table>
					</div>
				</div>
				<button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#details">View</button>
			</div>
		</div>
	</section>
</div>
<div class="modal" id="details">
		<div class="modal-dialog">
			<div class="modal-content">

			<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Details</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<table class="table table-bordered table-striped">
						
						<thead>
							<tr>
								<th>Total time</th>
								<th>Rate</th>
								<th>GST</th>
								<th>Total amount</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="time"></td>
								<td id="rate"></td>
								<td id="gst"></td>
								<td id="amount"></td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>

			</div>
		</div>
	</div>
@endsection


@section('footer')

	@include('layouts.footer')
    <script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script>
	<script src="{{ mix('/js/admin_timetable.js') }}"></script>

@endsection
