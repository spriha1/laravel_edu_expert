@extends('layouts.master')

@extends('layouts.sidenav')

@section('sidenav_content')

<li>
	<a href="teacher_dashboard.php">
		<i class="fa fa-address-card"></i> <span>Dashboard</span>
	</a>
</li>

<li>
	<a href="teacher_profile.php">
		<i class="fa fa-address-card"></i> <span>My Profile</span>
	</a>
</li>
<li>
	<a href="daily_teacher_timetable.php">
		<i class="fa fa-th"></i> <span>Daily Time Table</span>
	</a>
</li>
<li>
	<a href="weekly_teacher_timetable.php">
		<i class="fa fa-th"></i> <span>Weekly Time Table</span>
	</a>
</li>
<li>
	<a href="#">
		<i class="fa fa-users"></i> <span>Attendance</span>
	</a>
</li>
<li>
	<a href="student_timesheets.php">
		<i class="fa fa-th"></i> <span>Student Timesheet</span>
	</a>
</li>

@endsection
