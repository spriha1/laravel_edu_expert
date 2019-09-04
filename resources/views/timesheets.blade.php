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
							<div class="col-sm-3">
								<input id="date" class="datepicker">
							</div>
							<div class="col-sm-5">
							</div>
							<div class="col-sm-4">
								<select name="search" id="search">
									<option>Select User</option>
									@foreach($users as $user)
										<option value="{{$user->id}}">{{$user->firstname}} ({{ $user->email }})</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<input type="hidden" name="date_format" id="date_format" value="{{ Auth::user()->date_format }}">
					<div class="box-body">
						<input type="hidden" id="user_id" value="{{ Auth::id() }}">
						<input type="hidden" id="user_type" value="teacher">

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
								<input class="input" type="text" size="4" style="display:none;">
							</td>
							<td width="10%" dow="1" date="">
								<input class="input" type="text" size="4" style="display:none;">
							</td>
							<td width="10%" dow="2" date="">
								<input class="input" type="text" size="4" style="display:none;">
							</td>
							<td width="10%" dow="3" date="">
								<input class="input" type="text" size="4" style="display:none;">
							</td>
							<td width="10%" dow="4" date="">
								<input class="input" type="text" size="4" style="display:none;">
							</td>
							<td width="10%" dow="5" date="">
								<input class="input" type="text" size="4" style="display:none;">
							</td>
							<td width="10%" dow="6" date="">
								<input class="input" type="text" size="4" style="display:none;">
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
    <script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script>
	<script src="{{ mix('/js/admin_timetable.js') }}"></script>
	<script>
		$(document).ready(function() {
			$('#search').select2();
		});
	</script>

@endsection
