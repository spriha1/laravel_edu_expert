@extends('layouts.master')

@extends('layouts.sidenav')

@section('sidenav_content')


<li><a href="admin_dashboard.php">
		<i class="fa fa-address-card"></i> <span>Dashboard</span>
	</a>
</li>

<li>
	<a href="admin_profile.php">
		<i class="fa fa-address-card"></i> <span>My Profile</span>
	</a>
</li>
<li>
	<a href="pending_requests.php">
		<i class="fa fa-th"></i> <span>New Requests</span>
	</a>
</li>
<li>
	<a href="regd_users.php">
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
