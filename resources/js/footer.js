$(document).ready(function() {
	var url = window.location.pathname;
	$('.main-sidebar .sidebar ul li a[href="'+ url +'"]').parent().addClass('active');

	// $(".chosen").select2({
	// 	matcher: matchCustom
	// });

	var footer = document.getElementById("footer").getAttribute("footer");
	if (footer === "footer") {
		$(function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' /* optional */
			});
			$('.subject').select2({
				width: 'resolve'
			});
		});
	}

	else if (footer === "dashboard_footer") {
		$.widget.bridge('uibutton', $.ui.button);
		$('.datepicker').datepicker({
			format: $('#date_format').val()
		});
		$('.subject').select2({
			width: 'resolve'
		});
		$('._subject').select2({
			width: 'resolve'
		});
	}

	else if (footer === "profile_footer") {
		$.widget.bridge('uibutton', $.ui.button);
		$('#edit').click(function(){
			event.preventDefault();
			$(":input").attr("readonly", false);
			$("#date_format").attr("disabled", false);
			$("#edit").css('display', 'none');
			$("#update").css('display', 'block');
		});
		$('#change').click(function(){
			event.preventDefault();
			$("#pass").css('display', 'block');
			$("#edit").css('display', 'none');
			$("#update").css('display', 'block');
		});
		$('.start').click(function() {
			var task_id = $(this).closest('tr').attr('task_id');
			$(this).closest('tr').find('.timer').timer({
				seconds: 0,
				hidden: false
			});
			$('tbody tr[task_id=' + task_id + '] .start').css('display', 'none');
			$('tbody tr[task_id=' + task_id + '] .stop').css('display', 'table-row');
			$('tbody tr[task_id=' + task_id + '] .pause').css('display', 'table-row');
		})
		$('.stop').click(function() {
			var task_id = $(this).closest('tr').attr('task_id');
			var date = $('#date').val();
			var date_format = $('#date_format').val();
			$('tbody tr[task_id=' + task_id + '] .start').css('display', 'none');
			$('tbody tr[task_id=' + task_id + '] .stop').css('display', 'none');
			$('tbody tr[task_id=' + task_id + '] .pause').css('display', 'none');

			var time = $(this).closest('tr').find('.timer').data('seconds');
			$('tbody tr[task_id=' + task_id + '] .timer').timer('remove');
			var task_id = $(this).attr('task_id');
			var user_id = $('#user_id').val();
			var user_type = $('#user_type').val();

			$.post('/add_completion_time', {task_id: task_id, user_id: user_id, time: time, user_type: user_type, date: date, date_format: date_format})
		})
		$('.resume').click(function() {
			var task_id = $(this).closest('tr').attr('task_id');

			$('tbody tr[task_id=' + task_id + '] .resume').css('display', 'none');
			$('tbody tr[task_id=' + task_id + '] .stop').css('display', 'table-row');
			$('tbody tr[task_id=' + task_id + '] .pause').css('display', 'table-row');

			$(this).closest('tr').find('.timer').timer('resume');
		})
		$('.pause').click(function() {
			var task_id = $(this).closest('tr').attr('task_id');

			$('tbody tr[task_id=' + task_id + '] .pause').css('display', 'none');
			$('tbody tr[task_id=' + task_id + '] .stop').css('display', 'table-row');
			$('tbody tr[task_id=' + task_id + '] .resume').css('display', 'table-row');

			$(this).closest('tr').find('.timer').timer('pause');
		})

		$('.datepicker').datepicker({
			format: $('#date_format').val()
		});

		$('.subject').select2({
			width: 'resolve'
		});

		$('.day').select2({
			width: 'resolve'
		});
		$('#search').select2();
	}

	else if (footer === "timesheet_footer") {
		$.widget.bridge('uibutton', $.ui.button);
	}

	else if (footer === "forgot_password_footer") {
		$(document).ready(function() {
            $('button').click(function() {
                event.preventDefault();
                if ($('#username').val().trim() === "") {
                    $('#username').css("borderColor" , "red");
                    $("#alert").text("Please enter a username");
                    $("#alert").css("display" , "block");
                }
                else {
                    // console.log('hi');
                    var username = $('#username').val();
                    $.get("/fetch_info" , {q1: "username", q2: username} , function(data) {
                        if (Number(data) === 0) {
                            $('#username').css("borderColor" , "red");
                            $("#alert").text("This username does not exist");
                            $("#alert").css("display" , "block");
                        }
                        else {
                        	$('#login').submit();
                        }
                    }); 
                }    
            });
        });
	}
})