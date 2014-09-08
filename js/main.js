$(document).ready(function(){
	$('#sign_in_submit').click(function(){

		var name = $('#sign_in').find('[name=name]').val();
		var pw = $('#sign_in').find('[name=pw]').val();
		$.ajax({
			type: 'POST',
			url: '/index.php/user/sign_in',
			data: {name: name, pw: pw}, 
			dataType: 'json',
		})
		.done(function(j){
			if(j.status){
				location.reload();
			}else{
				$('#sign_in_msg').html("Invalid name or password !");
			}
		});
		
	});
	$('#sign_out_submit').click(function(){
		$.ajax({
			type: 'GET',
			url: '/index.php/user/sign_out',
			dataType: 'json',
		})
		.done(function(j){
			location.reload();
		});
	});
	$('#add_user_submit').click(function(){
		var userdata = {};
		var form = $('#add_user');
		$('#add_user_msg').html("");

		userdata.name = form.find('[name=name]').val();
		userdata.realname = form.find('[name=realname]').val();
		userdata.pw = form.find('[name=pw]').val();
		userdata.confirm = form.find('[name=confirm]').val();
		userdata.email = form.find('[name=email]').val();
		userdata.phone = form.find('[name=phone]').val();
		userdata.pages = form.find('[name=pages]').val();

		$.ajax({
			type: 'POST',
			url: '/index.php/user/add_user',
			data: userdata,
			dataType: 'json',
		})
		.done(function(j){
			if(j.status){
				alert("新增成功!");
				location.reload();
			}else{
				$('#add_user_msg').html(j.msg);
			}
		});
	});
	$('#member_directory').dataTable();
	
	
});
