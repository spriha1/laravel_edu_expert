$(document).ready(function() {
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	var date = new Date();
	$('.datepicker').datepicker('setDate', date);
	var date = $('#date').val(); 
	var user_id = $('#user_id').val();
	var user_type = $('#user_type').val();
	var date_format = $('#date_format').val();

	load_display_data(date,user_id,user_type,date_format);

	$('#share').click(function(event) {
		event.preventDefault();
		var user_id = $("#user_id").val();

		var date_format = $('#date_format').val();
		var date = $("#date").val();
		$.post('/add_shared_timesheets', {user_id: user_id, date: date, date_format: date_format});
	});

	$('.datepicker').datepicker().on('changeDate', function(e) {
		var date = e.format();
		var user_id = $('#user_id').val();
		var user_type = $('#user_type').val();
		var date_format = $('#date_format').val();

		$('.timetable').html("");
		load_display_data(date,user_id,user_type,date_format);
	})
})

function load_display_data(date,user_id,user_type,date_format) {
	$.post('/display_daily_timetable', {date: date, user_id: user_id, user_type: user_type, date_format: date_format}, function(result) {
		console.log(result);
		var response = JSON.parse(result);
		if (date_format === "yyyy/mm/dd") {
			date = date.split('/');
			date = new Date(date[0], date[1]-1, date[2]).getTime();
    	}
    	else if (date_format === "yyyy.mm.dd") {
    		date = date.split('.');
			date = new Date(date[0], date[1]-1, date[2]).getTime();
    	}
    	else if (date_format === "yyyy-mm-dd") {
    		date = date.split('-');
			date = new Date(date[0], date[1]-1, date[2]).getTime();
    	}
    	else if (date_format === "dd/mm/yyyy") {
    		date = date.split('/');
			date = new Date(date[2], date[1]-1, date[0]).getTime();
    	}
    	else if (date_format === "dd-mm-yyyy") {
    		date = date.split('-');
			date = new Date(date[2], date[1]-1, date[0]).getTime();
    	}
    	else if (date_format === "dd.mm.yyyy") {
    		date = date.split('.');
			date = new Date(date[2], date[1]-1, date[0]).getTime();
    	}
    	date = date/1000;
		var length = response.length;
		if (user_type === 'teacher') {
			for (var i = 0; i < length; i++) {
				let element = $(".editable").clone(true).css('display', 'table-row').removeClass('editable');
				element.attr('task_id', response[i].task_id);
				element.appendTo('.timetable');
				var task_id = response[i].task_id;

				var seconds = response[i].total_time;
				if (seconds > 0) {
					var hours = Math.floor(seconds / 3600);
					seconds = seconds - (hours * 3600);
					var minutes = Math.floor(seconds / 60);
					seconds = seconds - (minutes * 60);
					var time = hours + ':' + minutes + ':' + seconds;

					var _date = new Date(date * 1000);
					_date = _date.getDate() + '/' + (_date.getMonth()+1) + '/' + _date.getFullYear();
					var _on_date = response[i].on_date;
					_on_date = new Date(_on_date * 1000);
					_on_date = _on_date.getDate() + '/' + (_on_date.getMonth()+1) + '/' + _on_date.getFullYear();
		
					if (_date === _on_date) {
						$("tbody tr[task_id=" + task_id + "] .timer").text(time);
					}
					
				}

				$("tbody tr[task_id=" + task_id + "] .name").text(response[i].name);
				$("tbody tr[task_id=" + task_id + "] .class").text(response[i].class);
				$("tbody tr[task_id=" + task_id + "] .stop").attr('task_id', response[i].task_id);
			}
		}
		else if (user_type === 'student') {
			for (var i = 0; i < length; i++) {
				let element = $(".editable").clone(true).css('display', 'table-row').removeClass('editable');
				element.attr('task_id', response[i].task_id);
				element.appendTo('.timetable');
				var task_id = response[i].task_id;

				var seconds = response[i].total_time;
				if (seconds > 0) {
					var hours = Math.floor(seconds / 3600);
					seconds = seconds - (hours * 3600);
					var minutes = Math.floor(seconds / 60);
					seconds = seconds - (minutes * 60);
					var time = hours + ':' + minutes + ':' + seconds;

					var _date = new Date(date * 1000);
					_date = _date.getDate() + '/' + (_date.getMonth()+1) + '/' + _date.getFullYear();
					var _on_date = response[i].on_date;
					_on_date = new Date(_on_date * 1000);
					_on_date = _on_date.getDate() + '/' + (_on_date.getMonth()+1) + '/' + _on_date.getFullYear();
					console.log(_date)
					console.log(_on_date)
					if (_date == _on_date) {
						$("tbody tr[task_id=" + task_id + "] .timer").val(time);
					}
					
				}

				$("tbody tr[task_id=" + task_id + "] .name").text(response[i].name);
				$("tbody tr[task_id=" + task_id + "] .teacher").text(response[i].firstname);
				$("tbody tr[task_id=" + task_id + "] .stop").attr('task_id', response[i].task_id);
			}
		}
	});
}