$(document).ready(function() {
	$('body').submit(function(event) {

		if (event.target.id === 'registration') {
			
			event.preventDefault();
			var check,c = 0,size = 0;
			$('#registration input').not('.select2-search__field').each(function(){
			    check = $(this).val().trim();
				if (check === "") {
					$(this).css("borderColor" , "red");
					c++;
					console.log(this);
				}
			});
			$('#registration').find('select').each(function(){
			    check = $(this).val();
			    console.log(check);
				// if (check === "") {
				// 	$(this).css("borderColor" , "red");
				// 	c++;
				// 	console.log(this);
				// }
			});
			if (c > 0) {
				$("#alert").text("Please fill in the highlighted fields");
				$("#alert").css("display" , "block");
				event.preventDefault();
			}
			else {
				$.post('register' , $('#registration').serialize() , function(result){
					$("#alert").css("display" , "block");
					$("#alert").text(result);
				})
				.fail(function(result) {
					console.log(result.responseText);
				    var res = JSON.parse(result.responseText);
				    var errors = res.errors;
					// $('#alert').html(errors.fname + '<br>' + errors.lname + '<br>' + errors.username + '<br>' + errors.email + '<br>' + errors.password);
					
					$("#alert").css("display" , "block");
				});
			}
		}
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
				var password_pattern = /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/;
				if ($('#password').val() === "") {
					$('#password').css("borderColor" , "rgba(0,0,0,.125)");
				}

				else if (!password_pattern.test($('#password').val())) {
					$('#password').css("borderColor" , "red");
					$("#alert").text("Invalid password");
					$("#alert").css("display" , "block");
				}

				else {
					$('#password').css("borderColor" , "green");
				}
			}
			else if (event.target.id === 'username') {
				$("#info_username").css("display" , "none");
				var username_pattern = /^([a-zA-Z0-9@_]+)$/;
				var username = $('#username').val();
				$.get("fetch_info" , {q1: "username", q2: username} , function(data) {
					if (Number(data) === 1) {
						$('#username').css("borderColor" , "red");
						$("#alert").text("This username already exists");
						$("#alert").css("display" , "block");
					}
				});

				if ($('#username').val() === "") {
					$('#username').css("borderColor" , "rgba(0,0,0,.125)");
				}

				else if (!username_pattern.test($('#username').val())) {
					$('#username').css("borderColor" , "red");
					$("#alert").text("Invalid username");
					$("#alert").css("display" , "block");
				}

				else {
					$('#username').css("borderColor" , "green");
				}
			}
			else if (event.target.id === 'email') {
				$("#info_email").css("display" , "none");
				var email = $('#email').val();
				$.get("fetch_info" , {q1: "email", q2: email} , function(data) {
					if (Number(data) === 1) {
						$('#email').css("borderColor" , "red");
						$("#alert").text("This email already exists");
						$("#alert").css("display" , "block");
					}
				});
				if ($('#email').val() === "") {
					$('#email').css("borderColor" , "rgba(0,0,0,.125)");
				}

				else if ($('#email').val().indexOf("@") < 0 || $('#email').val().indexOf(".") < 0) {
					$('#email').css("borderColor" , "red");
					$("#alert").text("Invalid email");
					$("#alert").css("display" , "block");
				}

				else {
					$('#email').css("borderColor" , "green");
				}
			}
			else if (event.target.id === 'fname') {
				var name_pattern = /^([a-zA-Z]+)$/;
				if ($('#fname').val() === "") {
					$('#fname').css("borderColor" , "rgba(0,0,0,.125)");
				}

				else if (!name_pattern.test($('#fname').val())) {
					$('#fname').css("borderColor" , "red");
					$("#alert").text("Invalid first name");
					$("#alert").css("display" , "block");
				}

				else {
					$('#fname').css("borderColor" , "green");
				}
			}

			else if (event.target.id === 'lname') {
				var name_pattern = /^([a-zA-Z]+)$/;
				if ($('#lname').val() === "") {
					$('#lname').css("borderColor" , "rgba(0,0,0,.125)");
				}

				else if (!name_pattern.test($('#lname').val())) {
					$('#lname').css("borderColor" , "red");
					$("#alert").text("Invalid last name");
					$("#alert").css("display" , "block");
				}

				else {
					$('#lname').css("borderColor" , "green");
				}
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