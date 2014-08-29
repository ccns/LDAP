$(document).ready(function(){
	$('#signin_submit').click(function(){

		var name = $('#signin').find('[name=name]').val();
		var pw = $('#signin').find('[name=pw]').val();
		$.ajax({
			type: 'POST',
			url: '/index.php/user/sign_in',
			data: { name: name, pw: pw }, 
			dataType: 'json',
		})
		.done(function(j){
			if(j.status){
				location.reload();
			}else{
				$('#signin_msg').html("Invalid name or password !");
			}
		});
		
	});
	$('#signout_submit').click(function(){
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
