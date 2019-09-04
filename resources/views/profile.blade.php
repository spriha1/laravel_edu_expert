@extends('layouts.master')

@section('sidenav_content')
@if ($usertype === 'Admin')
	@include('layouts.admin_sidenav')
@elseif ($usertype === 'Teacher')
	@include('layouts.teacher_sidenav')
@elseif ($usertype === 'Student')
	@include('layouts.student_sidenav')
@endif
@endsection

@section('content')
<div class="content-wrapper">
	<br><br>
	<div class="col-md-6">
		<!-- Horizontal Form -->
		<div class="box box-info">
			<form class="form-horizontal" id="registration" name="registration" method="POST">
				<div id="alert" class='alert alert-danger' style="display: none;">
				</div>
				<div id="info" class='alert alert-success' style="display: none;">
				</div>
				@csrf
				<div class="box-body">
					<div class="form-group">
						<label for="fname" class="col-sm-3 control-label">First Name</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="fname" name="fname" readonly value="{{ Auth::user()->firstname }}">
						</div>
					</div>
					<div class="form-group">
						<label for="lname" class="col-sm-3 control-label">Last Name</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="lname" name="lname" readonly value="{{ Auth::user()->lastname }}">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-3"></div>
						<div class="col-sm-9">
							<div id="info_username" class='text-info' style="display: none;">
                			</div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="username" class="col-sm-3 control-label">Username</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="username" id="username" readonly value="{{ Auth::user()->username }}">
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-3 control-label">Email</label>
						<div class="col-sm-9">
							<input type="email" class="form-control" name="email" id="email" readonly value="{{ Auth::user()->email }}">
						</div>
					</div>

					@if ($usertype === 'Teacher')
					@foreach ($rates as $rate)
					<div class="form-group">
						<label for="rate" class="col-sm-3 control-label">Rate per hour</label>
						<div class="col-sm-9">
							<input type="number" class="form-control" name="rate" id="rate" readonly value="{{ $rate->rate }}">
						</div>
					</div>
					@endforeach
					@endif

					<div class="form-group">
						<label for="date_format" class="col-sm-3 control-label">Date Format</label>
						<div class="col-sm-9">
							<select name="date_format" id="date_format" class="form-control" disabled>
								<option value="yyyy-mm-dd" {{ Auth::user()->date_format === 'yyyy-mm-dd' ? 'selected="selected"' : '' }}>yyyy-mm-dd</option>

								<option value="yyyy/mm/dd" {{ Auth::user()->date_format === 'yyyy/mm/dd' ? 'selected="selected"' : '' }}>yyyy/mm/dd</option>

								<option value="yyyy.mm.dd" {{ Auth::user()->date_format === 'yyyy.mm.dd' ? 'selected="selected"' : '' }}>yyyy.mm.dd</option>

								<option value="dd-mm-yyyy" {{ Auth::user()->date_format === 'dd-mm-yyyy' ? 'selected="selected"' : '' }}>dd-mm-yyyy</option>

								<option value="dd/mm/yyyy" {{ Auth::user()->date_format === 'dd/mm/yyyy' ? 'selected="selected"' : '' }}>dd/mm/yyyy</option>

								<option value="dd.mm.yyyy" {{ Auth::user()->date_format === 'dd.mm.yyyy' ? 'selected="selected"' : '' }}>dd.mm.yyyy</option>
							</select>
						</div>
					</div>
					<div class="form-group" id="pass" style="display: none">
						<label for="password" class="col-sm-3 control-label">Password</label>
						<div class="col-sm-9">
							<input type="password" class="form-control" id="password" name="password">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-3"></div>
						<div class="col-sm-9">
							<div id="info_password" class='text-info' style="display: none;"></div>
						</div>
					</div>
					<div id="info_password" class='text-info' style="display: none;">
                	</div>
					<div class="form-group">
						<label for="address" class="col-sm-3 control-label">Address</label>
						<div id="geocoder" class="col-sm-9">
							<!-- <textarea name="address" id="address" style="width: 100%" value="{{ Auth::user()->address }}"></textarea> -->
						</div>
					</div>
					<input type="hidden" name="lat" id="lat" value="{{ Auth::user()->latitude }}">
					<input type="hidden" name="long" id="long" value="{{ Auth::user()->longitude }}">
					<input type="hidden" name="address" id="address" value="{{ Auth::user()->address }}">
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" id="change" class="btn btn-default">Change password</button>
					<button type="submit" id="edit" class="btn btn-info pull-right">Edit</button>
					<button type="submit" id="update" style="display:none;" class="btn btn-info pull-right">Update</button>
				</div>
				<!-- /.box-footer -->
			</form>
		</div>
	</div>
	<!-- <div class="col-md-6">
		<div id="googleMap" style="width:400px;height:400px;"></div>
	</div> -->
	<div class="col-md-6" id="map" style="height:60vh"></div>
</div>
@endsection


@section('footer')
@include('layouts.footer')

<script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script>

<script src="{{ mix('/js/edit.js') }}"></script>

@endsection