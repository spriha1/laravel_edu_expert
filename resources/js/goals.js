$(document).ready(function() {
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	var date = new Date();
	$('.datepicker').datepicker('setDate', date);
	date = $('#date').val();
	date = $(".datepicker").data('datepicker').getFormattedDate('yyyy-mm-dd');
	console.log(date);
	date = new Date(date);
	date = date.getTime()/1000;
	//var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
	var user_id = $('#user_id').val();
	var total_time = 0;
	load_display_data(date,user_id);

	$(".add_item").click(function(event) {
		event.preventDefault();
		$("#goal").css("display", "block");
		$(".add").css("display", "block");
		$(".add_item").css("display", "none");
	});

	$(".add").click(function(event) {
		event.preventDefault();
		$("#goal").css("display", "none");
		$(".add").css("display", "none");
		$(".add_item").css("display", "block");
		date = $('#date').val();
		date_format = $('#date_format').val();
		date = $(".datepicker").data('datepicker').getFormattedDate('yyyy-mm-dd');
		date = new Date(date);
		on_date = date.getTime()/1000;
		// if (date_format === "yyyy/mm/dd") {
		// 	var date = date.split("/");
		// 	var year = date[0];
		// 	var month = date[1]+1;
		// 	var day = date[2];
  //   	}
  //   	else if (date_format === "yyyy.mm.dd") {
  //   		var date = date.split(".");
		// 	var year = date[0];
		// 	var month = date[1]+1;
		// 	var day = date[2];
  //   	}
  //   	else if (date_format === "yyyy-mm-dd") {
  //   		var date = date.split("-");
		// 	var year = date[0];
		// 	var month = date[1]+1;
		// 	var day = date[2];
  //   	}
  //   	else if (date_format === "dd/mm/yyyy") {
  //   		var date = date.split("/");
		// 	var year = date[2];
		// 	var month = date[1]+1;
		// 	var day = date[0];
  //   	}
  //   	else if (date_format === "dd-mm-yyyy") {
  //   		var date = date.split("-");
		// 	var year = date[2];
		// 	var month = date[1]+1;
		// 	var day = date[0];
  //   	}
  //   	else if (date_format === "dd.mm.yyyy") {
  //   		var date = date.split(".");
		// 	var year = date[2];
		// 	var month = date[1]+1;
		// 	var day = date[0];
  //   	}
  //   	var date = new Date(year, month, day);
  //   	var time = date.getTime()/100;
		var goal = $("textarea").val();
		var user_id = $(".add").attr("user_id");
		$.post('add_goals', {goal: goal, user_id: user_id, on_date: on_date}, function(result) {
			var response = JSON.parse(result);
			let element = $(".editable").clone(true).css('display', 'block').removeClass('editable');
			element.find('.text').html(response[0].goal);
			element.find('.remove').attr('goal_id', response[0].id);;
			element.attr('goal_id', response[0].id);
			element.appendTo('.todo');
		});
		$("textarea").val("");
	});

	$(".check_goal").change(function(event) {
		event.preventDefault();
		var goal_id = $(this).closest('[goal_id]').attr("goal_id");
		$.post('update_goals', {goal_id: goal_id}, function(result) {
			var response = JSON.parse(result);
			console.log(response);
			var total_time = response[0].total_time;
			var time = new Date(null);
			time.setSeconds(response[0].total_time);
			var total_time = time.toISOString().substr(11, 8);
			console.log(total_time)

			$("ul li[goal_id=" + goal_id + "]").find('.time').css('visibility', 'visible');
			$("ul li[goal_id=" + goal_id + "]").find('.total_time').text(total_time);

		});
		
	});

	$(".remove").click(function(event) {
		//event.preventDefault();
		var goal_id = $(this).attr('goal_id');
		$.post('remove_goals', {goal_id: goal_id}, function() {
			$("ul li[goal_id=" + goal_id + "]").remove();
		});
	});

	// $("#date").change(function(event) {
	// 	var date = $(this).val();
	// 	var user_id = $('#user_id').val();
	// 	$('.todo').html("");
	// 	load_display_data(date,user_id);
	// });

	$('.datepicker').datepicker().on('changeDate', function(e) {
		var date = e.format();
		date = $(".datepicker").data('datepicker').getFormattedDate('yyyy-mm-dd');
		date = new Date(date);
		date = date.getTime()/1000;
		var user_id = $('#user_id').val();
		$('.todo').html("");
		load_display_data(date,user_id);
	})

	// $('#share').click(function(event) {
	// 	event.preventDefault();
	// 	var user_id = $("#user_id").val();
	// 	var date = $("#date").val();
	// 	$.post('add_shared_timesheets.php', {user_id: user_id, date: date, timesheet_check: 0});
	// });
})

function load_display_data(date,user_id) {
	$.post('display_goals', {date: date, user_id: user_id}, function(result) {
		var response = JSON.parse(result);
		var length = response.length;
		for (var i = 0; i < length; i++) {
			let element = $(".editable").clone(true).css('display', 'block').removeClass('editable');
			element.attr('goal_id', response[i].id);
			element.appendTo('.todo');
			goal_id = response[i].id;
			$("ul li[goal_id=" + goal_id + "] .text").html(response[i].goal);
			$("ul li[goal_id=" + goal_id + "] .remove").attr('goal_id', response[i].id);
			$("ul li[goal_id=" + goal_id + "] .time").attr('id', response[i].id);

			// element.find('.text').html(response[i].goal);
			// element.find('.remove').attr('goal_id', response[i].id);
			// element.find('.time').attr('id', response[i].id);
			if(response[i].check_status == 1) {
				$("ul li[goal_id=" + goal_id + "] .check_goal").attr('checked', true);

				//element.find('.check_goal').attr('checked', true);
				var time = new Date(null);
				time.setSeconds(response[i].total_time);
				total_time = time.toISOString().substr(11, 8);
				$("ul li[goal_id=" + goal_id + "] .time").css('visibility', 'visible');
				$("ul li[goal_id=" + goal_id + "] .time .total_time").text(total_time);

				// $('#'+response[i].id).css('visibility', 'visible');
				// $('#'+response[i].id+' .total_time').text(total_time);
			}
		}
	});
}