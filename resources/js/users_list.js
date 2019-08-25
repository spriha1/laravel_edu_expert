$(document).ready(function(){

	$(function () {
		$('#regd_users').DataTable({
		'paging'      : true,
		'lengthChange': true,
		'searching'   : true,
		'ordering'    : true,
		'info'        : true,
		'autoWidth'   : false
		})
	})
	
	$('.class').change(function(event){
		event.preventDefault();
		var value = $(this).val();
		var username = $(this).attr('username');
		$.post('update_class.php', {username: username, value: value});
	})
})