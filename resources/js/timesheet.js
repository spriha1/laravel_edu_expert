$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function () {
        $('#timesheet').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false
        });
    });
    
    $('.view').click(function() {
        var from_id   = $(this).attr('from_id');
        var of_date   = $(this).attr('of_date');
        var user_type = $(this).attr('user_type');
        $.post('/fetch_timesheet', {
            from_id: from_id, 
            of_date: of_date, 
            user_type: user_type
        }, 
        function(result) {
            var response = JSON.parse(result);
            var length   = response.length;
            $('#view_timesheet').html("");
            if (user_type == 'teacher') {
                for (var index = 0; index < length; index++) 
                {
                    var element = $('.timesheet_body').clone(true).removeClass('timesheet_body');
                    element.find('.number').text(index + 1);
                    element.find('.subject').text(response[index].name);
                    element.find('.class').text(response[index].class);
                    var time = new Date(null);
                    time.setSeconds(response[index].total_time);
                    var total_time = time.toISOString().substr(11, 8);
                    element.find('.total_time').text(total_time);
                    element.appendTo('#view_timesheet');
                }
            }

            else if (user_type == 'student') {
                for (var index = 0; index < length; index++) 
                {
                    var element = $('.timesheet_body').clone(true).removeClass('timesheet_body');
                    element.find('.number').text(index + 1);
                    element.find('.subject').text(response[index].name);
                    var time = new Date(null);
                    time.setSeconds(response[index].total_time);
                    var total_time = time.toISOString().substr(11, 8);
                    element.find('.total_time').text(total_time);
                    element.appendTo('#view_timesheet');
                }
            }
        });
    });
});