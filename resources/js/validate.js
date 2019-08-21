$(document).ready(function() {
	$('body').submit(function(event) {
		event.preventDefault();
		if (event.target.id === 'login') {
			var username = $('#username').val().trim();
			var password = $('#password').val().trim();
			if (username === "") {
				$('#username').css("borderColor" , "red");
			}

			if (password === "") {
				$('#password').css("borderColor" , "red");
			}

			if (username === "" || password === "") {
				$("#alert").text("Please fill in the highlighted fields");
				$("#alert").css("display" , "block");
			}
			else {
				$.post('ajax_login.php', $('#login').serialize(), function(result) {
					var response = JSON.parse(result)["msg"];
					var newToken = JSON.parse(result)["token"];
					$('#token').val(newToken);
					console.log(response);
					if (response === "Admin") {
						window.location.href = 'admin_dashboard.php';
					}
					else if (response === "Student") {
						window.location.href = 'student_dashboard.php';
					}
					else if (response === "Teacher") {
						window.location.href = 'teacher_dashboard.php';
					}
					else {
						$('#alert').text(response);
						$("#alert").css("display" , "block");
					}
				});
			}
		}

		if (event.target.id === 'registration') {
			console.log('hi');
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
				event.preventDefault();

				$.post('ajax_register.php' , $('#registration').serialize() , function(result){
					$('#alert').text(result);
					$("#alert").css("display" , "block");
				})
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

			if (event.target.id === 'username') {
				$("#info_username").css("display" , "none");
				var username_pattern = /^([a-zA-Z0-9@_]+)$/;
				var username = $('#username').val();
				$.get("fetch_info.php" , {q1: "username", q2: username} , function(data) {
					if (Number(data) === 1) {
						$('#username').css("borderColor" , "red");
						$("#alert").text("This username already exists");
						$("#alert").css("display" , "block");
					}
				})

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

			else if (event.target.id === 'password') {
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

			else if (event.target.id ==="email") {
				var email = $('#email').val();
				$.get("fetch_info.php", {q1: "email", q2: email}, function(data) {
					if (Number(data) === 1) {
						$('#email').css("borderColor" , "red");
						$("#alert").text("This email already exists");
						$("#alert").css("display" , "block");
					}
				})

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
	});

	$('.subject').change(function() {
		console.log($('.subject').val());
	})
})