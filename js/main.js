$(document).ready(function(){
	$('#sign-in-submit').click(sign_in_submit);

	function sign_in_submit(){

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
		
	}

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

	$('#sign-in').find('input').keydown(function(e){
		if(e.which == 13){
			sign_in_submit();	
		}
	});
});

