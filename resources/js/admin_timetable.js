$(document).ready(function() {
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	var user_id, user_type;
	var date = new Date();
	$('.datepicker').datepicker('setDate', date);
	var date = $('#date').val(); 
	// var user_id = $('#user_id').val();
	// var user_type = $('#user_type').val();
	var date_format = $('#date_format').val();

	

	$('#search').change(function() {
		user_id = $('#search').val();
		user_type = $('option:selected').attr('usertype');
		date = $('#date').val(); 
		date_format = $('#date_format').val();
		load_display_data(date,user_id,date_format,user_type);
	});

	// $('.input').blur(function() {
	// 	var time = $(this).val();
	// 	time = time.split(":");
	// 	time = parseInt(time[0], 10)*3600 + parseInt(time[1], 10)*60 + parseInt(time[2], 10);
	// 	var date = $(this).closest('td').attr('date');
	// 	var user_id = $('#user_id').val();
	// 	var task_id = $(this).closest('tr').attr('task_id');
	// 	var user_type = $('#user_type').val();
	// 	$.post('/update_completion_time', {time: time, date: date, user_id: user_id, task_id: task_id, user_type: user_type});
	// })

	$('.datepicker').datepicker().on('changeDate', function(e) {
		var date = e.format();
		// var user_id = $('#user_id').val();
		// var user_type = $('#user_type').val();
		var date_format = $('#date_format').val();

		$('.timetable').html("");
		load_display_data(date,user_id,date_format,user_type);
	})
})

function load_display_data(date,user_id,date_format,user_type) {
	// console.log(date);
	// console.log(user_id);
	// console.log(date_format);
	// console.log(user_type);

	$.post('/post_timesheets', {date: date, user_id: user_id, date_format: date_format, user_type: user_type}, function(result) {
		var response = JSON.parse(result);
		$('.timetable').html("");
		var len = response['original_dates'].length;
		for (var i = 0; i < len; i++) {
			var date = new Date(response['original_dates'][i] * 1000);
			var year = date.getFullYear();
			var month = date.getMonth() + 1;
			var day = date.getDate();
			if (day < 10) {
				day = '0' + day;
			}
			if (month < 10) {
				month = '0' + month;
			}
			if (date_format === "yyyy/mm/dd") {
				date = year + '/' + month + '/' + day;
	    	}
	    	else if (date_format === "yyyy.mm.dd") {
	    		date = year + '.' + month + '.' + day;
	    	}
	    	else if (date_format === "yyyy-mm-dd") {
	    		date = year + '-' + month + '-' + day;
	    	}
	    	else if (date_format === "dd/mm/yyyy") {
	    		date = day + '/' + month + '/' + year;
	    	}
	    	else if (date_format === "dd-mm-yyyy") {
	    		date = day + '-' + month + '-' + year;
	    	}
	    	else if (date_format === "dd.mm.yyyy") {
	    		date = day + '.' + month + '.' + year;
	    	}
			$('table thead #' + i).text(date);
		}

		var tasks = response[0];
		var length = tasks.length;
			for (var i = 0; i < length; i++) {
				let element = $(".editable").clone(true).css('display', 'table-row').removeClass('editable');
				element.attr('task_id', tasks[i][0].task_id);
				element.appendTo('.timetable');
				var task_id = tasks[i][0].task_id;

				var len = response['dates'].length;
				for(var k = 0; k < len; k++)
				{
					if(response['dates'][k] != 0) {
						$("tbody tr[task_id=" + task_id + "] td[dow=" + k + "]").attr('date', response['dates'][k]);
					}
					
				}
				var task = tasks[i][0].name + ' / ' + tasks[i][0].class;
				$("tbody tr[task_id=" + task_id + "] .task").text(task);
				var len = response[task_id].length;
				for(var j = 0; j < len; j++)
				{
					if(response[task_id][j].length != 0) 
					{
						var seconds = response[task_id][j][0].total_time;
						if (seconds != 0) {
							var hours = Math.floor(seconds / 3600);
							seconds = seconds - (hours * 3600);
							var minutes = Math.floor(seconds / 60);
							seconds = seconds - (minutes * 60);
							var time = hours + ':' + minutes + ':' + seconds;

						}
						else {
							var time = '0:0:0';
						}
						var task_id = response[task_id][j][0].task_id;
						$("tbody tr[task_id=" + task_id + "] td[date=" + response[task_id][j][0].on_date + "] input").val(time);
						$("tbody tr[task_id=" + task_id + "] td[dow=" + j + "] input").css('display', 'table-row');

					}
				}
			}
		// }

		// else if (user_type === 'Student') {
		// 	for (var i = 0; i < length; i++) {
		// 		let element = $(".editable").clone(true).css('display', 'table-row').removeClass('editable');
		// 		element.attr('task_id', tasks[i][0].task_id);
		// 		element.appendTo('.timetable');
		// 		var task_id = tasks[i][0].task_id;

		// 		var len = response['dates'].length;
		// 		for(var k = 0; k < len; k++)
		// 		{
		// 			if(response['dates'][k] != 0) {
		// 				$("tbody tr[task_id=" + task_id + "] td[dow=" + k + "]").attr('date', response['dates'][k]);
		// 			}
					
		// 		}
		// 		var task = tasks[i][0].name + ' / ' + tasks[i][0].firstname;
		// 		$("tbody tr[task_id=" + task_id + "] .task").text(task);
		// 		var len = response[task_id].length;
		// 		for(var j = 0; j < len; j++)
		// 		{
		// 			if(response[task_id][j].length != 0) 
		// 			{
		// 				var seconds = response[task_id][j][0].total_time;
		// 				if (seconds > 0) {
		// 					var hours = Math.floor(seconds / 3600);
		// 					seconds = seconds - (hours * 3600);
		// 					var minutes = Math.floor(seconds / 60);
		// 					seconds = seconds - (minutes * 60);
		// 					var time = hours + ':' + minutes + ':' + seconds;

		// 				}
		// 				var task_id = response[task_id][j][0].task_id;
		// 				$("tbody tr[task_id=" + task_id + "] td[date=" + response[task_id][j][0].on_date + "] input").val(time);
		// 				$("tbody tr[task_id=" + task_id + "] td[dow=" + j + "] input").css('display', 'table-row');

		// 			}
		// 		}
		// 	}
		// }
	});
}