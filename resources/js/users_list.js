$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function () {
        let view = $('#view').val();
        if (view == 'regd') {
            var url = '/get_regd_users';
        }

        else {
            var url = '/get_pending_requests';
        }

        var regd_users_table = $('#regd_users').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: url,
                method: 'POST',
                data: function(s){
                    s.user_type = $('#user_type').val() 

                }
            },
            order: [[0, 'asc']],
            columns: [
                { data: 'firstname', name: 'firstname'},
                { data: 'lastname', name: 'lastname'},
                { data: 'username', name: 'username'},
                { data: 'email', name: 'email'},
                { data: 'action', name: 'action', orderable:false, searchable:false}
            ]
        });
        
        $('body').on('click', '.change_status', function() {
            let id   = $(this).attr('user_id');
            let type = $(this).attr('type');
            $.get('/change_user_type/'+id+'/'+type, function(result) {
                if (result.success) {
                    toastr.success('The user has been' + type + 'ed');
                }
                else {
                    toastr.error('The user could not be ' + type + 'ed');
                }
                regd_users_table.draw();
            });
        });

        $('body').on('click', '.remove', function() {
            let id   = $(this).attr('user_id');
            $.get('/remove_users/'+id, function(result) {
                if (result.success) {
                    toastr.success('The user has been removed');
                }

                else {
                    toastr.error('The user could not be removed');
                }

                regd_users_table.draw();
            });
        });

        $('body').on('click', '.add', function() {
            let id   = $(this).attr('user_id');
            $.get('/add_users/', function(result) {
                if (result.success) {
                    toastr.success('The user has been added');
                }

                else {
                    toastr.error('The user could not be added');
                }
                regd_users_table.draw();
            });
        });

        $('#go').on('click', function() {
            event.preventDefault();
            regd_users_table.draw();
        });

    });
});