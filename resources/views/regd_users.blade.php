@extends('layouts.master')

@extends('layouts.sidenav')

@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection


@section('content')

<div class="content-wrapper">
	<br><br>
	<nav class="navbar navbar-transparent justify-content-center">
		<form class="form-inline" method="POST" action="regd_users">
		  	@csrf
			<div class="form-group mx-auto">
		    <select class="form-control mb-2 mr-sm-2" id="user_type" name="user_type">
		        <option value="0">Select User Type</option>
		       	@foreach($user_types as $user_type)
					<option value={{ $user_type->user_type }} {{ ($user_type->user_type === $search)?"selected":"" }}>{{ $user_type->user_type }}
					</option>
				@endforeach
	      	</select>
	      	</div>
	      	
	      	<div class="form-group mx-auto">
	      		<button class="btn btn-success form-control mr-sm-2 mb-2" type="submit">Go</button>
	      	</div>
	  </form>
	</nav>

	<br><br>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-body">
						<input type="hidden" id="username" name="username" value="{{ Auth::user()->username }}">
						<table id="regd_users" class="table table-bordered table-striped">
					    	<thead>
								<tr>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Username</th>
									<th>Email</th>
									<th></th>
									<th></th>
									<th>Assign Class</th>
								</tr>
							</thead>
						    @foreach ($results as $result)
						     	<tr>
									<td>{{ $result->firstname }}</td>
									<td>{{ $result->lastname }}</td>
									<td>{{ $result->username }}</td>
									<td>{{ $result->email }}</td>
									<td><a href="remove_users/{{ $result->id }}"><button class="btn btn-success">Remove</button></a></td>
									@if ($result->block_status==0)
										<td><a href="block_users/{{ $result->id }}"><button class="btn btn-success">Block</button></a></td>
									@elseif ($result->block_status==1)
											<td><a href="unblock_users/{{ $result->id }}"><button class="btn btn-success">Unblock</button></a></td>
									@endif
									<td>
									    <!-- assign class -->
									</td>
								</tr>
							@endforeach	
					    </table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

@endsection


@section('footer')


    <script src="{{ asset('js/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/dist/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dist/icheck.min.js') }}"></script>
    <script src="{{ asset('js/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/dist/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/dist/dashboard.min.js') }}"></script>
    <script src="{{ asset('js/dist/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dist/dataTables.bootstrap.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
	<script src="{{ mix('/js/users_list.js') }}"></script>

@endsection
