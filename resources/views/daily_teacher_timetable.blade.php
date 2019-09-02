@extends('layouts.master')

@section('sidenav_content')
@include('layouts.teacher_sidenav')
@endsection

@section('content')
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<!-- <input class="date" id="date" type="date"> -->
						<input id="date" class="datepicker">
						<button type="button" id="share" class="btn btn-success pull-right">Submit</button>
					</div>
					<input type="hidden" name="date_format" id="date_format" value="{{ Auth::user()->date_format }}">
					<div class="box-body">
						<input type="hidden" id="user_id" value="{{ Auth::id() }}">
						<input type="hidden" id="user_type" value="teacher">

						<table id="timetable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th width="25%">Subject</th>
									<th width="25%">Class</th>
									<th width="25%"></th>
									<th width="25%"></th>
								</tr>
							</thead>
							<tbody class="timetable">
							</tbody>
							
						</table>
						<table style="display:none">
						<tr class="editable" width="25%" task_id="" style="display:none;">
							<td class="name" width="25%"></td>
							<td class="class" width="25%"></td>
							<td width="25%"><input class="timer" type="text" value=""></td>
							<td>
								<button class="btn btn-info start">Start</button>
								<button class="btn btn-info stop" style="display:none" task_id="">Stop</button>
								<button class="btn btn-info pause" style="display:none">Pause</button>
								<button class="btn btn-info resume" style="display:none">Resume</button>
							</td>
						</tr>
					</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection


@section('footer')

	@include('layouts.footer')
    <!-- <script src="{{ asset('js/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/dist/bootstrap.min.js') }}"></script> -->
    <!-- <script src="{{ asset('js/dist/icheck.min.js') }}"></script> -->
    <!-- <script src="{{ asset('js/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/dist/adminlte.min.js') }}"></script> -->
    <!-- <script src="{{ asset('js/dist/dashboard.min.js') }}"></script> -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/timer.jquery/0.7.0/timer.jquery.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script> -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script> -->
    <script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script>
	<script src="{{ mix('/js/daily_timetable.js') }}"></script>

@endsection
