@extends('layouts.master')

@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		
	<input type="hidden" id="user_id" name="user_id" value="{{ Auth::id() }}">
	</section>
	<!-- Main content -->
	<section class="content">
	
		<div class="row">
			<!-- Left col -->
			<section class="col-lg-7 connectedSortable">
				
				<!-- TO DO List -->
				<div class="box box-primary">
					
					<div class="box-body">
						<ul class="todo-list append_class">
							<li class="clone" class_id="" style="display:none">
								<span class="text"></span>
								<div class="tools">
									<i class="fa fa-edit edit" data-toggle="modal" data-target="#edit_class"></i>
									<i class="fa fa-trash-o remove"></i>
								</div>
							</li>
						</ul>
						
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix no-border">
						<button type="button" class="btn btn-default add_item pull-right"><i class="fa fa-plus"></i> Add new class</button>
					</div>
				</div>
				<!-- /.box -->
			</section>
			<!-- /.Left col -->
			<!-- right col (We are only adding the ID to make the widgets sortable)-->
			<div class="modal" id="edit_class">
				<div class="modal-dialog">
					<div class="modal-content">

					<!-- Modal Header -->
						<div class="modal-header">
							<!-- <h4 class="modal-title">Modal Heading</h4> -->
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<!-- Modal body -->
						<div class="modal-body">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Subject</th>
										<th>Teacher</th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody id="view_subjects">
					
								</tbody>
								<tr subject_id="" class_id="" class="subjects_body" style="display:none">
									<td class="subject_name"></td>
									<td class="teacher"></td>
									<td><a><i class="fa fa-trash-o remove_subject"></i></a></td>
									<td><a class="edit_subject">Edit</a></td>
								</tr>
							</table>
						</div>

						<!-- Modal footer -->
						<div class="modal-footer">
							<div class="box box-info _add_class" style="display:none">
								<form class="form-horizontal" id="_add_class" name="_add_class" method="POST">
									<input type="hidden" name="class" value="">
									<div class="box-body _append_teacher">
										<div class="form-group">
											<label for="subjects" class="col-sm-3 control-label">Subject</label>
											<div class="col-sm-9">
												
												<select class="_subject" name="subjects[]" multiple="multiple" style="width:100%">
													@foreach ($subjects as $subject)
													<option value="{{ $subject->id }}">{{ $subject->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div id="_append_teacher">
										</div>
										<div class="form-group _editable" style="display:none;">
											<label for="" class="col-sm-3 control-label"></label>
											<div class="col-sm-9">
												<select class="_teacher form-control" name="">
													<option value=""></option>
												</select>
											</div>
										</div>
									</div>
									<!-- /.box-body -->
									<div class="box-footer">
										<button type="submit" id="_add" class="btn btn-info pull-right">Add</button>
									</div>
									<!-- /.box-footer -->
								</form>
							</div>
							
							<div id="edit_subject" style="display:none;">
								<div class="col-sm-4">

									<select class="teacher_ form-control pull-left" subject_id="" class_id="" name="">
									</select>
										<option class="_clone" value=""></option>


								</div>
								<div class="col-sm-2">
									<button class="btn btn-success">Update</button>
								</div>
							</div>
							<button class="btn btn-success add_subject">Add subject</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						</div>

					</div>
				</div>
			</div>
			<!-- right col -->
		</div>
		<!-- /.row (main row) -->
	</section>
	<!-- /.content -->
	<div class="col-md-6">
		<!-- Horizontal Form -->
		<div class="box box-info add_class" style="display:none">
			<form class="form-horizontal" id="add_class" name="add_class" method="POST">
				<div id="alert" class='alert alert-success' style="display: none;">
				</div>
				<div class="box-body append_teacher">
					<div class="form-group">
						<label for="class" class="col-sm-3 control-label">Class</label>
						<div class="col-sm-9">
							<input type="number" class="form-control" id="class" name="class">
						</div>
					</div>
					<div class="form-group">
						<label for="subjects" class="col-sm-3 control-label">Subject</label>
						<div class="col-sm-9">
							<select class="subject" id="subjects" name="subjects[]" multiple="multiple" style="width:100%">
								@foreach ($subjects as $subject)
									<option value="{{ $subject->id }}">{{ $subject->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div id="append_teacher">
						
					</div>
					<div class="form-group editable" style="display:none;">
						<label for="" class="col-sm-3 control-label"></label>
						<div class="col-sm-9">
							<select class="teacher form-control" name="">
								<option value=""></option>
							</select>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" id="add" class="btn btn-info pull-right">Add</button>
				</div>
				<!-- /.box-footer -->
			</form>
		</div>
	</div>
</div>
<!-- /.content-wrapper -->

</div>
@endsection


@section('footer')

	@include('layouts.footer')
    <!-- <script src="{{ asset('js/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/dist/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dist/icheck.min.js') }}"></script>
    <script src="{{ asset('js/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/dist/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/dist/dashboard.min.js') }}"></script> -->

   <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script> -->
    <script id="footer" footer="dashboard_footer" src="{{ mix('/js/footer.js') }}"></script>
	<script src="{{ mix('/js/manage_class.js') }}"></script>

@endsection
