$(document).ready(function(){
	$('#add-user-submit').click(function(){
		var userdata = {};
		var form = $('#add-user');
		var confirm_pw = form.find('[name=confirm]').val();
		$('#add-user-msg').html('');


		userdata.name = form.find('[name=name]').val();
		userdata.realname = form.find('[name=realname]').val();
		userdata.pw = form.find('[name=pw]').val();
		userdata.email = form.find('[name=email]').val();
		userdata.phone = form.find('[name=phone]').val();
		userdata.pages = form.find('[name=pages]').val();
		userdata.priv = form.find('[name=priv]').val();

		if(userdata.pw != confirm_pw){
			$('#add-user-msg').html('Password and confirmation password do no match.');	
			return;
		}

		$.ajax({
			type: 'POST',
			url: '/index.php/user/add_user',
			data: userdata,
			dataType: 'json',
		})
		.done(function(j){
			if(j.status){
				alert('新增成功!');
				location.reload();
			}else{
				$('#add-user-msg').html(j.msg);
			}
		});
	});

});

