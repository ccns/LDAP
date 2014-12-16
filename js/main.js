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
			location.replace('/');
		});
	});

	$('#sign-in').find('input').focusin(function(){
		$('#sign-in-msg').html('');	
	})
	.keydown(function(e){
		if(e.which == 13){
			sign_in_submit();	
		}
		$('#sign-in-msg').html('');	
	});

	$( window ).konami({
		cheat: function(){
			var img = $('#mimg');
			var width = $(document).width() - img.width();
			var height = $(document).height();
			var dir = Math.round(Math.random())*2 -1
			var ep = Math.random()
			var pos = {};

			pos.top = (height*(dir+1) + (dir-1)*img.height())/2;	
			pos.left = Math.round(ep*width);
			img.removeClass('hide');
			img.css(pos);
			if(dir > 0){
				img.addClass('yflip');
			}
			img.animate({
				top: "+=" + (-dir)*img.height()*2/3,
			},
			800,
			function(){
				$(this).animate({
					top: "+=" + dir*img.height()*2/3,
				},
				800,
				function(){
					$(this).addClass('hide');
					$(this).removeClass('yflip');
				});
			});
		}
	});
});

