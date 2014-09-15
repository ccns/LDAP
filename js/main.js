$(document).ready(function(){
	$('#sign-in-submit').click(function(){

		var name = $('#sign-in').find('[name=name]').val();
		var pw = $('#sign-in').find('[name=pw]').val();
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
				$('#sign-in-msg').html('Invalid name or password !');
			}
		});
		
	});
	$('#sign-out-submit').click(function(){
		$.ajax({
			type: 'GET',
			url: '/index.php/user/sign_out',
			dataType: 'json',
		})
		.done(function(j){
			location.reload();
		});
	});
});

