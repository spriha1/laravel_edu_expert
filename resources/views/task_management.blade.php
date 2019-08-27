@extends('layouts.master')

@extends('layouts.sidenav')

@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection

@section('content')
<div class="content-wrapper">
	<br><br>
	<div class="col-md-6">
		<!-- Horizontal Form -->
		<div class="box box-info">
			<form class="form-horizontal" id="task" name="task" method="POST">
				<div id="alert" class='alert alert-success' style="display: none;">
				</div>
				<img src="load.gif" id="spinner" style="display:none; width:20%; height:20%">
				<input type="hidden" name="date_format" id="date_format" value="{{ Auth::user()->date_format }}">
				
				<div class="box-header with-border">
	            	<h3 class="box-title">Add tasks</h3>
	            </div>
				<div class="box-body">
					<div class="form-group">
						<label for="class" class="col-sm-3 control-label">Class</label>
						<div class="col-sm-9">
							<select class="form-control mb-2 mr-sm-2 class" name="class" id="class">
						    	@foreach ($classes as $class)
						        <option value={{ $class->class }}>{{ $class->class }}</option>
						        @endforeach
					      	</select>
						</div>
					</div>
					<div class="form-group">
						<label for="subject" class="col-sm-3 control-label">Subject</label>
						<div class="col-sm-9">
							<select multiple="multiple" class="form-control subject" id="subject" name="subject[]">
	            					
					      	</select>
					      	<option class="clone" value=""></option>
						</div>
					</div>

					<div class="form-group">
						<label for="start_date" class="col-sm-3 control-label">Start Date</label>
						<div class="col-sm-9">
							<input id="start_date" name="start_date" class="datepicker">
						</div>
					</div>

					<div class="form-group">
						<label for="end_date" class="col-sm-3 control-label">End Date</label>
						<div class="col-sm-9">
							<input id="end_date" name="end_date" class="datepicker">
						</div>
					</div>
					
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-info pull-right">Add</button>
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
    <!-- <script src="{{ asset('js/dist/icheck.min.js') }}"></script> -->
    <script src="{{ asset('js/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/dist/adminlte.min.js') }}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/timer.jquery/0.7.0/timer.jquery.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script> -->
	<script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script>
	<script src="{{ mix('/js/task.js') }}"></script>
    <!-- <script src="{{ asset('js/dist/dashboard.min.js') }}"></script> -->

   <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script id="footer" footer="dashboard_footer" src="{{ mix('/js/footer.js') }}"></script>
	<script src="{{ mix('/js/goals.js') }}"></script> -->

@endsection
