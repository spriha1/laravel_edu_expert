$(document).ready(function() {
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	var user = $('#user_id').val();
	var user_id, user_type;
	var date = new Date();
	$('.datepicker').datepicker('setDate', date);
	var date = $('#date').val(); 

	var date_format = $('#date_format').val();

	$('#search').change(function() {
		user_id = $('#search').val();
		user_type = $('option:selected').attr('usertype');
		date = $('#date').val();
		date_format = $('#date_format').val();
		var year = parseInt(get_year(date, date_format));
		var d = format_date(date, date_format);
		var week = getNumberOfWeek(d);
		$.get('/fetch_request_status', {user_id: user_id, week: week, year: year}, function(result) {
			var response = JSON.parse(result);
			if (response[0]) {
				var status = response[0].name;
				if (status == 'Pending') {
					$('#accept').css('display', 'inline');
					$('#reject').css('display', 'inline');
				}
				else if (status == 'Approved') {
					$('#accept').css('display', 'none');
					$('#reject').css('display', 'inline');
					$('.badge').text('Approved');
				}
				else if (status == 'Rejected') {
					$('.badge').text('Rejected');
					$('#accept').css('display', 'inline');
					$('#reject').css('display', 'none');
				}
			}
			else {
				$('.badge').text('');
				$('#accept').css('display', 'none');
				$('#reject').css('display', 'none');
			}
		})
		load_display_data(date,user_id,date_format,user_type);
	});

	$('#accept').click(function() {
		user_id = $('#search').val();
		date = $('#date').val();
		date_format = $('#date_format').val();
		var year = parseInt(get_year(date, date_format));
		var d = format_date(date, date_format);
		var week = getNumberOfWeek(d);
		$.post('/update_request_status', {user_id: user_id, week: week, status: "Approved", year: year, user: user}, function(result) {
			if(result) {
				$('#accept').css('display', 'none');
				$('#reject').css('display', 'inline');
				$('.badge').text('Approved');
			}
		})
	})

	$('#reject').click(function() {
		user_id = $('#search').val();
		date = $('#date').val();
		date_format = $('#date_format').val();
		var year = parseInt(get_year(date, date_format));
		var d = format_date(date, date_format);
		var week = getNumberOfWeek(d);
		$.post('/update_request_status', {user_id: user_id, week: week, status: "Rejected", year: year, user: user}, function(result) {
			if(result) {
				$('.badge').text('Rejected');
				$('#accept').css('display', 'inline');
				$('#reject').css('display', 'none');
			}
		})
	})

	$('.datepicker').datepicker().on('changeDate', function(e) {
		var date = e.format();
		user_id = $('#search').val();
		var date_format = $('#date_format').val();
		var year = parseInt(get_year(date, date_format));
		var d = format_date(date, date_format);
		var week = getNumberOfWeek(d);
		$.get('/fetch_request_status', {user_id: user_id, week: week, year: year}, function(result) {
			var response = JSON.parse(result);
			if (response[0]) {
				var status = response[0].name;
				if (status == 'Pending') {
					$('#accept').css('display', 'inline');
					$('#reject').css('display', 'inline');
				}
				else if (status == 'Approved') {
					$('#accept').css('display', 'none');
					$('#reject').css('display', 'inline');
					$('.badge').text('Approved');
				}
				else if (status == 'Rejected') {
					$('.badge').text('Rejected');
					$('#accept').css('display', 'inline');
					$('#reject').css('display', 'none');
				}
			}
			else {
				$('.badge').text('');
				$('#accept').css('display', 'none');
				$('#reject').css('display', 'none');
			}
		})
		$('.timetable').html("");
		load_display_data(date,user_id,date_format,user_type);
	})
})

function format_date(date, date_format) {
	switch (date_format) {
		case "yyyy/mm/dd":
			date = date.split('/');
			date = new Date(date[0], date[1]-1, date[2]);
			break;
		case "yyyy.mm.dd":
			date = date.split('.');
			date = new Date(date[0], date[1]-1, date[2]);
			break;
		case "yyyy-mm-dd":
			date = date.split('-');
			date = new Date(date[0], date[1]-1, date[2]);
			break;
		case "dd/mm/yyyy":
			date = date.split('/');
			date = new Date(date[2], date[1]-1, date[0]);
			break;
		case "dd-mm-yyyy":
			date = date.split('-');
			date = new Date(date[2], date[1]-1, date[0]);
			break;
		case "dd.mm.yyyy":
			date = date.split('.');
			date = new Date(date[2], date[1]-1, date[0]);
			break;
	}
	return date;
}

function get_year(date, date_format) {
	switch (date_format) {
		case "yyyy/mm/dd":
			date = date.split('/');
			var year = date[0];
			break;
		case "yyyy.mm.dd":
			date = date.split('.');
			var year = date[0];
			break;
		case "yyyy-mm-dd":
			date = date.split('-');
			var year = date[0];
			break;
		case "dd/mm/yyyy":
			date = date.split('/');
			var year = date[2];
			break;
		case "dd-mm-yyyy":
			date = date.split('-');
			var year = date[2];
			break;
		case "dd.mm.yyyy":
			date = date.split('.');
			var year = date[2];
			break;
	}
	return year;
}

function getNumberOfWeek(date) {
    var today = new Date(date);
    var firstDayOfYear = new Date(today.getFullYear(), 0, 1);
    var pastDaysOfYear = (today - firstDayOfYear) / 86400000;
    return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
}

function load_display_data(date,user_id,date_format,user_type) {

	$.post('/post_timesheets', {date: date, user_id: user_id, date_format: date_format, user_type: user_type}, function(result) {
		var response = JSON.parse(result);
		console.log(response)
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
			switch (date_format) {
				case "yyyy/mm/dd":
					date = year + '/' + month + '/' + day;
					break;
				case "yyyy.mm.dd":
					date = year + '.' + month + '.' + day;
					break;
				case "yyyy-mm-dd":
					date = year + '-' + month + '-' + day;
					break;
				case "dd/mm/yyyy":
					date = day + '/' + month + '/' + year;
					break;
				case "dd-mm-yyyy":
					date = day + '-' + month + '-' + year;
					break;
				case "dd.mm.yyyy":
					date = day + '.' + month + '.' + year;
					break;
			}
			$('table thead #' + i).text(date);
		}

		var tasks = response[0];
		var length = tasks.length;
		var sum = 0;
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
			// var sum = 0;
			for(var j = 0; j < len; j++)
			{
				if(response[task_id][j].length != 0) 
				{
					var seconds = response[task_id][j][0].total_time;
					sum = sum + seconds;
					if (seconds != 0) {
						var hours = Math.floor(seconds / 3600);
						seconds = seconds - (hours * 3600);
						var minutes = Math.floor(seconds / 60);
						seconds = seconds - (minutes * 60);
						var time = hours + ':' + minutes + ':' + seconds;
					}
					else {
						var time = '00:00:00';
					}
					var task_id = response[task_id][j][0].task_id;
					$("tbody tr[task_id=" + task_id + "] td[date=" + response[task_id][j][0].on_date + "]").text(time);
				}
			}
		}
		console.log(sum)

	});
}