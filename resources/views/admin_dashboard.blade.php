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
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
		Dashboard
		</h1>
	<input type="hidden" id="user_id" name="user_id" value="{{ session('id') }}">
	@foreach($result as $res)
		<input type="hidden" name="date_format" id="date_format" value="{{ $res->date_format }}">
	@endforeach
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-lg-4 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-aqua">
					<div class="inner">
						
						<p>New Requests</p>
					</div>
					<div class="icon">
						<i class="ion ion-person-add"></i>
					</div>
					<a href="pending_requests" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-4 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-green">
					<div class="inner">
						
						<p>Registered Users</p>
					</div>
					<div class="icon">
						<i class="ion ion-person"></i>
					</div>
					<a href="regd_users" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-4 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						
						<p>Teacher Timesheets</p>
					</div>
					<div class="icon">
						<i class="ion ion-person"></i>
					</div>
					<a href="teacher_timesheets.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- ./col -->
			
			<!-- ./col -->
		</div>
		<!-- /.row -->
		<!-- Main row -->
		<div class="row">
			<!-- Left col -->
			<section class="col-lg-7 connectedSortable">
				
				<!-- TO DO List -->
				<div class="box box-primary">
					<div class="box-header">
						<i class="ion ion-clipboard"></i>
						<h3 class="box-title">Plan for the day</h3>
						<!-- <input class="pull-right date" id="date" type="date"> -->
						<input class="datepicker" id="date">
						<!-- <div class="box-tools pull-right">
							<ul class="pagination pagination-sm inline">
								<li><a href="#">&laquo;</a></li>
								<li><a href="#">1</a></li>
								<li><a href="#">2</a></li>
								<li><a href="#">3</a></li>
								<li><a href="#">&raquo;</a></li>
							</ul>
						</div> -->
					</div>
					<!-- /.box-header -->
					<div class="box-body" id="plan">
						<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
						<ul class="todo-list todo">
							
						</ul>
						<li class="editable" goal_id="" style="display:none">
							<input type="checkbox" class="check_goal">			
							<span class="text"></span>
							<small class="label label-danger time" id="" style="visibility: hidden"><i class="fa fa-clock-o total_time"></i></small>
							<div class="tools">
								<!-- <i class="fa fa-edit"></i> -->
								<i class="fa fa-trash-o remove" goal_id=""></i>
							</div>
						</li>

						<ul class="todo-list">
							<li name="goal" id="goal" style="display:none;">
								<textarea style="width: 100%"></textarea>
							</li>
						</ul>
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix no-border">
						<button type="button" style="display: none" class="btn btn-success pull-right add" user_id="{{ session('id') }}">Add</button>
						<button type="button" class="btn btn-default add_item pull-right"><i class="fa fa-plus"></i> Add item</button>
					</div>
				</div>
				<!-- /.box -->
			</section>
			<!-- /.Left col -->
			<!-- right col (We are only adding the ID to make the widgets sortable)-->
			<section class="col-lg-5 connectedSortable">
				
				
			</section>
			<!-- right col -->
		</div>
		<!-- /.row (main row) -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- </div> -->
@endsection


@section('footer')


    <script src="{{ asset('js/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/dist/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dist/icheck.min.js') }}"></script>
    <script src="{{ asset('js/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/dist/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/dist/dashboard.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script id="footer" footer="dashboard_footer" src="{{ mix('/js/footer.js') }}"></script>
	<script src="{{ mix('/js/goals.js') }}"></script>

@endsection
