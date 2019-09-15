$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('#task').submit(function(event) {
        event.preventDefault();
        $("#spinner").css('display','block');
        $.post('/add_timetable', $('#task').serialize(), function(result) {
            $('#spinner').css('display', 'none');
            $('#alert').text(result).css('display', 'block');
            $('.datepicker').val('');
            $('.subject').val('');
            $('.subject').html('');
            $('.subject').select2('destroy').select2();
            $('#class').val('');
        });
    });

    $('#teacher').change(function() {
        var teacher_id = $(this).val();
        $('.class').val('');
        $('.class').html('');
        $.post('/fetch_teacher_class', {teacher_id: teacher_id}, function(result) {
            var response = JSON.parse(result);
            var length = response.length;
            for(var index = 0; index < length; index++)
            {
                var element = $('.clone_').clone(true).removeClass('clone_');
                element.attr('value', response[index].class);
                element.text(response[index].class);
                element.appendTo('.class');
            }

            $('#class').trigger('change');
        });

    }).trigger('change')

    $('#class').change(function() {
        var class_id = $(this).val();
        var teacher_id = $('#teacher').val();
        $('.subject').val('');
        $('.subject').html('');
        $('.subject').select2('destroy').select2();
        $.post('/fetch_teacher_class_subjects', {
            class_id: class_id, 
            teacher_id: teacher_id
        }, 
        function(result) {
            var response = JSON.parse(result);
            var length = response.length;
            for(var index = 0; index < length; index++)
            {
                var element = $('.clone').clone(true).removeClass('clone');
                element.attr('value', response[index].id);
                element.text(response[index].name);
                element.appendTo('.subject');
            }
        });
    });
});

function format_date(date) {
    var today = new Date(date);
    var year  = today.getFullYear();
    var month = today.getMonth()+1;
    var date  = today.getDate();
    if (month < 10 && date < 10)
    {
        var date = year+'-0'+month+'-0'+date;
    }

    else if (month < 10)
    {
        var date = year+'-0'+month+'-'+date;
    }

    else if (date < 10)
    {
        var date = year+'-'+month+'-0'+date;
    }
    
    return date;
}