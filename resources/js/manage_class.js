$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.get('display_class', function(result) {
        var response = JSON.parse(result);
        var length   = response.length;
        for (var index = 0; index < length; index++) {
            let element = $(".clone").clone(true).css('display', 'block').removeClass('clone');
            element.find('.text').text(response[index].class);
            element.attr('class_id', response[index].class);
            element.appendTo('.append_class');
        }
    });
        
    $(".add_item").click(function(event) {
        event.preventDefault();
        $('#append_teacher').html("");
        $('.append_teacher #class').val("");
        $('.append_teacher .subject').val('');
        $('.append_teacher .subject').select2('destroy').select2();
        $(".add_class").css("display", "block");
    });

    $('.subject').on('select2:select', function (e) {
        var data = e.params.data;
        var id = data.id; //value of options
        var text = data.text;
        $.post('fetch_teachers', {subject_id: id}, function(result) {
            var response = JSON.parse(result);
            let element  = $(".editable").clone(true).css('display', 'block').removeClass('editable');
            element.find('label').text(text);
            element.find('label').attr('for', id);
            element.find('select').attr('name', id);
            for(var index = 0; index < response.length; index++)
            {
                let element2 = $(".editable option").clone(true);
                element2.attr('value', response[index].id);
                element2.html(response[index].firstname);
                element2.appendTo(element.find('select'))
            }
            element.appendTo('#append_teacher');
        })
        .fail(function() {
            toastr.error('The required information for the specified teachers could not be fetched');
        });
    });

    $('.subject').on('select2:unselect', function(e) {
        var data = e.params.data;
        var id   = data.id;
        $('.append_teacher label[for=' + id + ']').remove();
        $('.append_teacher select[name=' + id + ']').remove();
    });

    $("#add").click(function(event) {
        event.preventDefault();
        $(".add_class").css("display", "none");
        $.post('add_class', $('#add_class').serialize(), function(result) {
            var response = JSON.parse(result);
            let element  = $(".clone").clone(true).css('display', 'block').removeClass('clone');
            element.find('.text').text(response[0].class);
            element.attr('class_id', response[0].class);
            element.appendTo('.append_class');
        })
        .fail(function() {
            toastr.error('The class could not be added');
        });
    });

    $(".remove").click(function(event) {
        var class_id = $(this).closest('li').attr('class_id');
        $.post('remove_class', {class_id: class_id}, function() {
            $("ul li[class_id=" + class_id + "]").remove();
        })
        .fail(function() {
            toastr.error('The class could not be removed');
        });
    });

    $('.edit').click(function(event) {
        var class_id = $(this).closest('li').attr('class_id');
        $('._add_class').css('display', 'none');
        $('#edit_subject').css('display', 'none');
        $('#edit_subject select').val("");
        $("._add_class").find('form input').val(class_id);
        $.post('fetch_class_details', {class: class_id}, function(result) {
            var response = JSON.parse(result);
            var length = response.length;
            $('#view_subjects').html("");
            for (var index = 0; index < length; index++) 
            {
                var element = $('.subjects_body').clone(true).css('display', 'table-row').removeClass('subjects_body');
                element.find('.subject_name').text(response[index].name);
                element.find('.teacher').text(response[index].firstname);
                element.attr('subject_id', response[index].subjectid);
                element.attr('class_id', response[index].class);
                element.appendTo('#view_subjects');
            }
        })
        .fail(function() {
            toastr.error('The class details could not be fetched');
        });
    });

    $(".remove_subject").click(function(event) {
        var class_id = $(this).closest('tr').attr('class_id');
        var subject_id = $(this).closest('tr').attr('subject_id');
        $.post('remove_class_subject', {class_id: class_id, subject_id: subject_id}, function() {
            $("table tr[subject_id=" + subject_id + "]").remove();
        })
        .fail(function() {
            toastr.error('The subject could not be removed');
        });
    });

    $(".add_subject").click(function(event) {
        event.preventDefault();
        $('#_append_teacher').html("");
        $('._append_teacher ._subject').val('');
        $('._append_teacher ._subject').select2('destroy').select2();
        $("._add_class").css("display", "block");
    });

    $('._subject').on('select2:select', function (e) {
        var data = e.params.data;
        var id   = data.id; //value of options
        var text = data.text;
        $.post('fetch_teachers', {subject_id: id}, function(result) {
            var response = JSON.parse(result);
            let element  = $("._editable").clone(true).css('display', 'block').removeClass('_editable');
            element.find('label').text(text);
            element.find('label').attr('for', id);
            element.find('select').attr('name', id);
            for(var index = 0; index < response.length; index++)
            {
                let element2 = $("._editable option").clone(true);
                element2.attr('value', response[index].id);
                element2.html(response[index].firstname);
                element2.appendTo(element.find('select'))
            }
            element.appendTo('#_append_teacher');
        })
        .fail(function() {
            toastr.error('The required information could not be fetched');
        });
    });

    $('._subject').on('select2:unselect', function(e) {
        var data = e.params.data;
        var id   = data.id;
        $('._append_teacher label[for='+id+']').remove();
        $('._append_teacher select[name='+id+']').remove();
    });

    $("#_add").click(function(event) {
        event.preventDefault();
        $("._add_class").css("display", "none");
        $.post('add_class_subject', $('#_add_class').serialize(), function(result) {
            var response = JSON.parse(result);
            var element = $('.subjects_body').clone(true).css('display', 'table-row').removeClass('subjects_body');
            element.find('.subject_name').text(response[0].name);
            element.find('.teacher').text(response[0].firstname);
            element.attr('subject_id', response[0].subjectid);
            element.attr('class_id', response[0].class);
            element.appendTo('#view_subjects');
        })
        .fail(function() {
            toastr.error('The subject could not be added');
        });
    });

    $('.edit_subject').click(function() {
        $('#edit_subject').css('display', 'block');
        var subject_id = $(this).closest('tr').attr('subject_id');
        var class_id   = $(this).closest('tr').attr('class_id');
        $('#edit_subject select').attr('subject_id', subject_id);
        $('#edit_subject select').attr('class_id', class_id);
        $('#edit_subject select').html("");
        $.post('fetch_teachers', {subject_id: subject_id}, function(result) {
            var response = JSON.parse(result);
            for(var index = 0; index < response.length; index++)
            {
                let element = $("#edit_subject ._clone").clone(true).removeClass('_clone');
                element.attr('value', response[index].id);
                element.html(response[index].firstname);
                element.appendTo('.teacher_')
            }
        })
        .fail(function() {
            toastr.error('The required information could not be fetched');
        });
    });

    $('#edit_subject button').click(function(){
        $('#edit_subject').css('display', 'none');
        var subject_id = $('#edit_subject select').attr('subject_id');
        var class_id   = $('#edit_subject select').attr('class_id');
        var teacher_id = $('#edit_subject select').val();
        $.post('update_teacher', {subject_id: subject_id, class_id: class_id, teacher_id: teacher_id}, function(result) {
            var response = JSON.parse(result);
            $('.modal-body tr[subject_id='+subject_id+'] .teacher').text(response[0].firstname);
        })
        .fail(function() {
            toastr.error('The teacher details could not be updated');
        });
    });
});