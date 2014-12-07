$(document).ready(function(){
	$('#forgot-pw-submit').click(function(){
		var userdata = {};
		var form = $('#forgot-pw-form');
		$('#forgot-pw-form .warning').html('');

		userdata.name = form.find('[name=name]').val();
		userdata.email = form.find('[name=email]').val();

		if(!userdata.name.length || !userdata.email.length){
			$('#forgot-pw-form .warning').html('Name and Email are required.');	
			return;
		}

		$.ajax({
			type: 'POST',
			url: '/index.php/user/forgot_pw_proc',
			data: userdata,
			dataType: 'json',
		})
		.done(function(j){
			if(j.status){
				alert('信件已寄出.');
			}else{
				$('#forgot-pw-form .warning').html(j.msg);
			}
		});
	});

});

