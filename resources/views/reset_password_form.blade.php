<div class="container" style="text-align: center">
	<div class="card bg-secondary mx-auto" style="width: 50%">
		<div class="card-body">
			<form method="POST" action="/reset_password">
				@csrf
			    <div class="form-group">
			      <input type="password" class="form-control" id="password" placeholder="Enter New Password" name="password">
			  	</div>
			    <div class="form-group">
			    	<button type="submit" class="btn btn-success">Reset Password</button>
			    </div>
			</form>
		</div>
	</div>
</div>