$(document).ready(function(){
	$('#member-directory').dataTable();
	$('.del').click(function(){
		var obj = $(this);
		var uid = obj.attr('name');
		$.ajax({
			url: '/index.php/user/del_user/'+uid,
			dataType: 'json',
		})
		.done(function(j){
			obj.parent().parent().remove();
		});
	});
});

