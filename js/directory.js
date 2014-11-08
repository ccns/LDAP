$(document).ready(function(){
	var table = $('#member-directory').dataTable();
	$('.del').click(function(){
		var obj = $(this);
		var name = obj.attr('rel');
		var uid = obj.attr('name');
		if(!confirm('確定要刪除 '+name+' ?')){
			return;
		}
		$.ajax({
			url: '/index.php/user/del_user/'+uid,
			dataType: 'json',
		})
		.done(function(j){
			if(j.status){
				obj.parents('tr').fadeOut('fast',function(){
					$(this).remove();
				});	
			}
			if(j.reload){
				location.reload();
			}
		});
	});
});

