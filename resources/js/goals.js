$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var date = new Date();

    $('.datepicker').datepicker('setDate', date);
    date           = $('#date').val();
    date           = $(".datepicker").data('datepicker').getFormattedDate('yyyy-mm-dd');
    date           = new Date(date);
    date           = date.getTime()/1000;
    var user_id    = $('#user_id').val();
    var total_time = 0;
    load_display_data(date, user_id);

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

        date        = $('#date').val();
        date_format = $('#date_format').val();
        date        = $(".datepicker").data('datepicker').getFormattedDate('yyyy-mm-dd');
        date        = new Date(date);
        on_date     = date.getTime()/1000;
        var goal    = $("textarea").val();
        var user_id = $(".add").attr("user_id");

        $.post('add_goals', {
            goal: goal, 
            user_id: user_id, 
            on_date: on_date
        },
        function(result) {
            var response = JSON.parse(result);
            let element = $(".editable").clone(true).css('display', 'block').removeClass('editable');
            element.find('.text').html(response.goal);
            element.find('.remove').attr('goal_id', response.id);
            element.attr('goal_id', response.id);
            element.appendTo('.todo');
            toastr.success('Goal added successfully');

        })
        .fail(function(response) {
            if (response.status == 422) {
                toastr.error('Please fill the fields properly');
            } else {
                toastr.error('Goal could not be added');
            }
        });

        $("textarea").val("");
    });

    $(".check_goal").change(function(event) {
        event.preventDefault();
        var goal_id = $(this).closest('[goal_id]').attr("goal_id");
        $.post('update_goals', {goal_id: goal_id}, function(result) {
            var response   = JSON.parse(result);
            var total_time = response[0].total_time;
            var time       = new Date(null);
            time.setSeconds(response[0].total_time);
            var total_time = time.toISOString().substr(11, 8);
            $("ul li[goal_id=" + goal_id + "]").find('.time').css('visibility', 'visible');
            $("ul li[goal_id=" + goal_id + "]").find('.total_time').text(total_time);
        })
        .fail(function(response) {
            if (response.status == 422) {
                toastr.error('Goal could not be updated');
            }
        });
    });

    $(".remove").click(function(event) {
        var goal_id = $(this).attr('goal_id');
        $.post('remove_goals', {goal_id: goal_id}, function() {
            $("ul li[goal_id=" + goal_id + "]").remove();
        })
        .fail(function(response) {
            if (response.status == 422) {
                alert('Goal could not be added');
            }
        });
    });

    $('.datepicker').datepicker().on('changeDate', function(e) {
        var date    = e.format();
        date        = $(".datepicker").data('datepicker').getFormattedDate('yyyy-mm-dd');
        date        = new Date(date);
        date        = date.getTime()/1000;
        var user_id = $('#user_id').val();
        $('.todo').html("");
        load_display_data(date, user_id);
    });
});

function load_display_data(date, user_id) {
    $.post('display_goals', {
        date: date, 
        user_id: user_id
    }, 
    function(result) {
        var response = JSON.parse(result);
        var length   = response.length;

        for (var index = 0; index < length; index++) {
            let element = $(".editable").clone(true).css('display', 'block').removeClass('editable');
            element.attr('goal_id', response[index].id);
            element.appendTo('.todo');
            goal_id = response[index].id;

            $("ul li[goal_id=" + goal_id + "] .text").html(response[index].goal);
            $("ul li[goal_id=" + goal_id + "] .remove").attr('goal_id', response[index].id);
            $("ul li[goal_id=" + goal_id + "] .time").attr('id', response[index].id);

            if(response[index].check_status == 1) {
                $("ul li[goal_id=" + goal_id + "] .check_goal").attr('checked', true);
                var time = new Date(null);
                time.setSeconds(response[index].total_time);
                total_time = time.toISOString().substr(11, 8);
                $("ul li[goal_id=" + goal_id + "] .time").css('visibility', 'visible');
                $("ul li[goal_id=" + goal_id + "] .time .total_time").text(total_time);
            }
        }
    })
    .fail(function(response) {
        if (response.status == 422) {
            toastr.error('Goal could not be displayed');
        }
    });
}