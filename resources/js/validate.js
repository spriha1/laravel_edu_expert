$(document).ready(function() {
	function validation()
	{
		jQuery.validator.addMethod("onlyalpha", function(value, element) {
			return this.optional( element ) || /^([a-zA-Z]+)$/.test( value );
		}, 'Only alphabetic characters are allowed');

		jQuery.validator.addMethod("username", function(value, element) {
			return this.optional( element ) || /^([a-zA-Z0-9@_]+)$/.test( value );
		}, 'Please enter a valid username');

		jQuery.validator.addMethod("password", function(value, element) {
			return this.optional( element ) || /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/.test( value );
		}, 'Please enter a valid password');

		$("#registration").validate({
			rules: {
				'fname': {
					'required': true,
					'onlyalpha': true
				},
				'lname': {
					'required': true,
					'onlyalpha': true
				},
				'user_type': 'required',
				'email': {
					'required': true,
					'email': true
				},
				'password': {
					'required': true,
					'password': true
				},
				'username': {
					'required': true,
					'username': true
				}
			}
		});
	}
	


	$('body').submit(function(event) {
		event.preventDefault();
		validation();
		if ($('#registration').valid()) {
			$.post('register' , $('#registration').serialize() , function(result){
				$("#alert").css("display" , "block");
				$("#alert").text(result);
			});
		}

		// if (event.target.id === 'registration') {
			
		// 	var check,c = 0,size = 0;
		// 	$('#registration input').not('.select2-search__field').each(function(){
		// 	    check = $(this).val().trim();
		// 		if (check === "") {
		// 			$(this).css("borderColor" , "red");
		// 			c++;
		// 			console.log(this);
		// 		}
		// 	});
		// 	$('#registration').find('select').each(function(){
		// 	    check = $(this).val();
		// 	    console.log(check);
		// 		// if (check === "") {
		// 		// 	$(this).css("borderColor" , "red");
		// 		// 	c++;
		// 		// 	console.log(this);
		// 		// }
		// 	});
		// 	if (c > 0) {
		// 		$("#alert").text("Please fill in the highlighted fields");
		// 		$("#alert").css("display" , "block");
		// 		event.preventDefault();
		// 	}
		// 	else {
		// 		$.post('register' , $('#registration').serialize() , function(result){
		// 			$("#alert").css("display" , "block");
		// 			$("#alert").text(result);
		// 		})
		// 		.fail(function(result) {
		// 			console.log(result.responseText);
		// 		    var res = JSON.parse(result.responseText);
		// 		    var errors = res.errors;
		// 			// $('#alert').html(errors.fname + '<br>' + errors.lname + '<br>' + errors.username + '<br>' + errors.email + '<br>' + errors.password);
					
		// 			$("#alert").css("display" , "block");
		// 		});
		// 	}
		// }
	});

	$('body').click(function() {
		if (event.target.id === 'password' && event.target.closest("form").getAttribute("id") === 'registration') {
			var msg = "The password :<br> Must be a minimum of 8 characters<br>Must contain at least 1 number<br>Must contain at least one uppercase character<br>Must contain at least one lowercase character";
			$("#info_password").html(msg);
			$("#info_password").css("display" , "block");
		}

		if (event.target.id === 'username' && event.target.closest("form").getAttribute("id") === 'registration') {
			var msg = "The username can contain letters, digits, @ and _";
			$("#info_username").text(msg);
			$("#info_username").css("display" , "block");
		}
	});


	$('input').blur(function() {
		
		if (event.target.closest("form").getAttribute("id") === 'registration') {
			if (event.target.id === 'password') {
				$("#info_password").css("display" , "none");
			}
			else if (event.target.id === 'username') {
				$("#info_username").css("display" , "none");
				var username = $('#username').val();
				$.get("/fetch_info" , {q1: "username", q2: username} , function(data) {
					if (Number(data) === 1) {
						$('#username').css("borderColor" , "red");
						$("#alert").text("This username already exists");
						$("#alert").css("display" , "block");
					}
				});
			}
			else if (event.target.id === 'email') {
				$("#info_email").css("display" , "none");
				var email = $('#email').val();
				$.get("/fetch_info" , {q1: "email", q2: email} , function(data) {
					if (Number(data) === 1) {
						$('#email').css("borderColor" , "red");
						$("#alert").text("This email already exists");
						$("#alert").css("display" , "block");
					}
				});
			}
		}
	});	

	$('#user_type').change(function(event) {
		if ($('#user_type').val() === 'Teacher') {
			$('.subject').closest('div').css('display', 'block');
		}
		if ($('#user_type').val() === 'Student') {
			$('.subject').closest('div').css('display', 'none');
		}
	});

	$('.subject').change(function() {
		console.log($('.subject').val());
	})
})