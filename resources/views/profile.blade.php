@extends('layouts.master')

@extends('layouts.sidenav')

@section('sidenav_content')


<li>
	<a href="admin_dashboard">
		<i class="fa fa-address-card"></i> <span>Dashboard</span>
	</a>
</li>

<li>
	<a href="profile">
		<i class="fa fa-address-card"></i> <span>My Profile</span>
	</a>
</li>
<li>
	<a href="pending_requests">
		<i class="fa fa-th"></i> <span>New Requests</span>
	</a>
</li>
<li>
	<a href="regd_users">
		<i class="fa fa-users"></i> <span>Registered Users</span>
	</a>
</li>
<li>
	<a href="teacher_timesheets.php">
		<i class="fa fa-th"></i> <span>Teacher Timesheet</span>
	</a>
</li>

<li>
	<a href="task_management.php">
		<i class="fa fa-th"></i> <span>Task Management</span>
	</a>
</li>
<li>
	<a href="system_management.php">
		<i class="fa fa-th"></i> <span>System Management</span>
	</a>
</li>

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
				@csrf
				<div class="box-body">
					@foreach ($results as $result)
					<div class="form-group">
						<label for="fname" class="col-sm-3 control-label">First Name</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="fname" name="fname" readonly value="{{ $result->firstname }}">
						</div>
					</div>
					<div class="form-group">
						<label for="lname" class="col-sm-3 control-label">Last Name</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="lname" name="lname" readonly value="{{ $result->lastname }}">
						</div>
					</div>
					<div class="form-group">
						<label for="username" class="col-sm-3 control-label">Username</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="username" id="username" readonly value="{{ $result->username }}">
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-3 control-label">Email</label>
						<div class="col-sm-9">
							<input type="email" class="form-control" name="email" id="email" readonly value="{{ $result->email }}">
						</div>
					</div>
					<div class="form-group">
						<label for="date_format" class="col-sm-3 control-label">Date Format</label>
						<div class="col-sm-9">
							<select name="date_format" id="date_format" class="form-control" disabled>
								<option value="yyyy-mm-dd" {{ $result->date_format === 'yyyy-mm-dd' ? 'selected="selected"' : '' }}>yyyy-mm-dd</option>

								<option value="yyyy/mm/dd" {{ $result->date_format === 'yyyy/mm/dd' ? 'selected="selected"' : '' }}>yyyy/mm/dd</option>

								<option value="yyyy.mm.dd" {{ $result->date_format === 'yyyy.mm.dd' ? 'selected="selected"' : '' }}>yyyy.mm.dd</option>

								<option value="dd-mm-yyyy" {{ $result->date_format === 'dd-mm-yyyy' ? 'selected="selected"' : '' }}>dd-mm-yyyy</option>

								<option value="dd/mm/yyyy" {{ $result->date_format === 'dd/mm/yyyy' ? 'selected="selected"' : '' }}>dd/mm/yyyy</option>

								<option value="dd.mm.yyyy" {{ $result->date_format === 'dd.mm.yyyy' ? 'selected="selected"' : '' }}>dd.mm.yyyy</option>
							</select>
						</div>
					</div>
					<div class="form-group" id="pass" style="display: none">
						<label for="password" class="col-sm-3 control-label">Password</label>
						<div class="col-sm-9">
							<input type="password" class="form-control" id="password" name="password">
						</div>
					</div>
				@endforeach
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
</div>
@endsection


@section('footer')

<script src="{{ asset('js/dist/jquery.min.js') }}"></script>
<script src="{{ asset('js/dist/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/dist/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/dist/adminlte.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/timer.jquery/0.7.0/timer.jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script>

<script src="{{ mix('/js/edit.js') }}"></script>

@endsection