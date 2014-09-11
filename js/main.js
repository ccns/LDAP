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
/* user page */
	$('#add-user-submit').click(function(){
		var userdata = {};
		var form = $('#add-user');
		$('#add-user-msg').html('');

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
				alert('新增成功!');
				location.reload();
			}else{
				$('#add-user-msg').html(j.msg);
			}
		});
	});

	$('body').on('click',ui_focusout);

	function ui_focusout(e){
		$('div.edit-block').each(_recover);
		_hide_pw_field();
	}

	$('a.edit-user').on('click',_edit);
		
	function _edit(e){
		ui_focusout();
		var view_text = $(this).parent().find('.view-text');
		var edit_text = $(this).parent().find('.edit-text');	
		var p = $(this).parent();	

		if(!view_text.hasClass('hide')){
			view_text.addClass('hide');
		}
		if(edit_text.hasClass('hide')){
			edit_text.removeClass('hide');
			edit_text.focus();
		}


		p.on('click',function(e){ e.stopPropagation(); });
		$(this).html('submit').off().on('click',_submit);
	}	
	function _submit(e){
		var obj = $(this);
		var field = $(this).parent().find('.val').attr('name'); 
		var val = $(this).parent().find('.val').val();

		var view_text = $(this).parent().find('.view-text');
		var edit_text = $(this).parent().find('.edit-text');	
		var p = $(this).parent();

/*
		$.ajax({
			type: 'POST',
			url: '/index.php/user/edit_user',
			data: {field: field, val: val}, 
			dataType: 'json',
			error: function(){
				$(this).val('edit');
				$(this).off(_submit).on(_edit);
			},
		})
		.done(function(j){
			if(j.status){
			}else{
				obj.parent().find('.warning').html(j.msg);
			}
*/
			p.each(_recover);

//		});
	}
	function _recover(e){
		var view_text = $(this).find('.view-text');
		var btn = $(this).find('.edit-user');
		var edit_text = $(this).find('.edit-text');;

		if(view_text.hasClass('hide')){
			view_text.removeClass('hide');
		}
		if(!edit_text.hasClass('hide')){
			edit_text.addClass('hide');
			btn.html('edit').off().on('click',_edit);
			$(this).off();
		}
	}

	$('#change-password').on('click',_show_pw_field);
	
	function _show_pw_field(e){
		ui_focusout();
		e.stopPropagation();
		var pw_field = $('#pw-field');	
		var pw_block = $('#pw-block');
		if(pw_field.hasClass('hide')){
			pw_field.removeClass('hide');
			pw_block.on('click',function(e){ e.stopPropagation(); });
			$('#change-password').html('submit').off().on('click',_submit_pw);
		}
	}	
	function _submit_pw(){
		
			_hide_pw_field();
	}
	function _hide_pw_field(){
		var pw_field = $('#pw-field');	
		var pw_block = $('#pw-block');
		if(!pw_field.hasClass('hide')){
			pw_field.addClass('hide');
			pw_block.off();
			$('#change-password').html('change password').off().on('click',_show_pw_field);
		}
	}
/* md page */
	$('#member-directory').dataTable();
});

