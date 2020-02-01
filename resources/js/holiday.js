$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#holiday').submit(function(event) {
        event.preventDefault();
        $.post('add_holiday', $('#holiday').serialize(), function(result) {
            $('#alert').text(result);
            $('#alert').css('display', 'block');
        })
        .fail(function() {
            toastr.error('The holiday could not be added');
        });
    });
});