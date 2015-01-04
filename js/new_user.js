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
	$('#auto-gen').click(function(e){
		e.stopPropagation();
		var gen = (Math.random()*10).toString(36).replace(/[^a-z0-9]+/g, '').substr(3, 8);	
		$('#add-user').find('[name=pw]').val(gen);
		$('#add-user').find('[name=confirm]').val(gen);
		$('#show-pw').prop('checked',true);
		show_pw();
	});	
	$('#show-pw').click(function(e){
		e.stopPropagation();
		if($('#show-pw').prop('checked')){
			show_pw();
		}else{
			hide_pw();
		}
	});
	function show_pw(){
		$('#add-user').find('[name=pw]').attr('type','text');
		$('#add-user').find('[name=confirm]').attr('type','text');
	}
	function hide_pw(){
		$('#add-user').find('[name=pw]').attr('type','password');
		$('#add-user').find('[name=confirm]').attr('type','password');
	}
	$('body').click(function(){
		$('#show-pw').prop('checked',false);
		hide_pw();	
	});
});

