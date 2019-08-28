@extends('layouts.master')

@extends('layouts.sidenav')

@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		
	<input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-aqua">
					<div class="inner">
						<!-- <h3>0</h3> -->
						<p>Manage Subjects</p>
					</div>
					<!-- <div class="icon">
						<i class="ion ion-person-add"></i>
					</div> -->
					<a href="/manage_subjects" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-green">
					<div class="inner">
						<!-- <h3>0</h3> -->
						<p>Manage class</p>
					</div>
					<!-- <div class="icon">
						<i class="ion ion-person"></i>
					</div> -->
					<a href="/manage_class" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<!-- <h3>0</h3> -->
						<p>Add tasks</p>
					</div>
					<!-- <div class="icon">
						<i class="ion ion-person"></i>
					</div> -->
					<a href="/task_management" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<!-- <h3>0</h3> -->
						<p>Add holidays</p>
					</div>
					<!-- <div class="icon">
						<i class="ion ion-person"></i>
					</div> -->
					<a href="/holiday" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- ./col -->
			
			<!-- ./col -->
		</div>
		<!-- /.row -->
		<!-- Main row -->
		
		<!-- /.row (main row) -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

</div>
@endsection


@section('footer')


    <script src="{{ asset('js/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/dist/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dist/icheck.min.js') }}"></script>
    <script src="{{ asset('js/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/dist/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/dist/dashboard.min.js') }}"></script>

   <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script id="footer" footer="dashboard_footer" src="{{ mix('/js/footer.js') }}"></script>
	<script src="{{ mix('/js/goals.js') }}"></script> -->

@endsection
