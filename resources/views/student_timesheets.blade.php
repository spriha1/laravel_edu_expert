@extends('layouts.master')

@extends('layouts.sidenav')

@section('sidenav_content')
@include('layouts.teacher_sidenav')
@endsection

@section('content')
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-body">
						
						<table id="timesheet" class="table table-bordered table-striped">
							
						<thead>
							<tr>
								<th>First Name</th>
								<th>Username</th>
								<th>Date</th>
								<th></th>
							</tr>
						</thead>
						@foreach ($results as $result) {
					     	<tr>
								<td>{{ $result->firstname }}</td>
								<td>{{ $result->username }}</td>
								<td>{{ format_date($result->of_date, Auth::user()->date_format) }}</td>
								<td>
									<button type="button" user_type="student" class="btn btn-success view" from_id="{{ $result->from_id }}" of_date="{{ $result->of_date }}" data-toggle="modal" data-target="#view_timesheets">View</button>
								</td>
							</tr>
						@endforeach
					    </table></div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="modal" id="view_timesheets">
		<div class="modal-dialog">
			<div class="modal-content">

			<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Timesheet</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<table class="table table-bordered table-striped">
						
						<thead>
							<tr>
								<th></th>
								<th>Subject</th>
								<th>Total time taken</th>
							</tr>
						</thead>
						<tbody id="view_timesheet">
							
						</tbody>
						<tr id="" class="timesheet_body">
							<td class="number"></td>
							<td class="subject"></td>
							<td class="total_time"></td>
						</tr>
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


    <script src="{{ asset('js/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/dist/bootstrap.min.js') }}"></script>
    <!-- <script src="{{ asset('js/dist/icheck.min.js') }}"></script> -->
    <script src="{{ asset('js/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/dist/adminlte.min.js') }}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/timer.jquery/0.7.0/timer.jquery.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script> -->
	<!-- <script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script> -->
	<script src="{{ mix('/js/timesheet.js') }}"></script>
    <!-- <script src="{{ asset('js/dist/dashboard.min.js') }}"></script> -->

   <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script id="footer" footer="dashboard_footer" src="{{ mix('/js/footer.js') }}"></script>
	<script src="{{ mix('/js/goals.js') }}"></script> -->

@endsection
