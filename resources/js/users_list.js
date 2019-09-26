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
                method: 'POST'
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
    });
});