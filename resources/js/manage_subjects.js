$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    load_display_data();
    $(".add_item").click(function(event) {
        event.preventDefault();
        $("#subject").css("display", "block");
        $(".add").css("display", "block");
        $(".add_item").css("display", "none");
    });

    $(".add").click(function(event) {
        event.preventDefault();
        $("#subject").css("display", "none");
        $(".add").css("display", "none");
        $(".add_item").css("display", "block");
        var subject = $("textarea").val();
        $.post('add_subject', {subject: subject}, function(result) {
            var response = JSON.parse(result);
            let element  = $(".editable").clone(true).css('display', 'block').removeClass('editable');
            element.find('.text').html(response[0].name);
            element.attr('subject_id', response[0].id);
            element.appendTo('.todo');
        });

        $("textarea").val("");
    });

    $(".remove").click(function(event) {
        var subject_id = $(this).closest('li').attr('subject_id');
        $.post('remove_subject', {subject_id: subject_id}, function() {
            $("ul li[subject_id=" + subject_id + "]").remove();
        });
    });
});

function load_display_data() {
    $.get('display_subjects', function(result) {
        var response = JSON.parse(result);
        var length   = response.length;
        for (var i = 0; i < length; i++) {
            let element = $(".editable").clone(true).css('display', 'block').removeClass('editable');
            element.attr('subject_id', response[i].id);
            element.appendTo('.todo');
            subject_id = response[i].id;
            $("ul li[subject_id=" + subject_id + "] .text").html(response[i].name);
        }
    });
}
