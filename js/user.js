$(document).ready(function(){
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
		$('div.edit-block').each(_recover_edit);
		_hide_pw_field();
		
	}

	$('a.edit-user').on('click',_edit);
		
	function _edit(e){
		ui_focusout();
		$('div.edit-block').find('.warning').html('');

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
		$(this).html('submit').off().on('click',_submit_edit);
	}	
	function _submit_edit(e){
		var obj = $(this);

		var data = {};
		data.field = $(this).parent().find('input').attr('name'); 
		data.val = $(this).parent().find('input').val();

		var view_text = $(this).parent().find('.view-text');
		var p = $(this).parent();

		$.ajax({
			type: 'POST',
			url: '/index.php/user/edit_user',
			data: data, 
			dataType: 'json',
			error: function(){
				p.each(_recover_edit);
			},
		})
		.done(function(j){
			if(j.status){
				view_text.html(val);
			}else{
				obj.parent().find('.warning').html(j.msg);
			}
			p.each(_recover_edit);
		});
	}
	function _recover_edit(e){
		var view_text = $(this).find('.view-text');
		var btn = $(this).find('.edit-user');
		var edit_text = $(this).find('.edit-text');;

		if(view_text.hasClass('hide')){
			view_text.removeClass('hide');
		}
		if(!edit_text.hasClass('hide')){
			edit_text.addClass('hide');
			edit_text.val('');
			btn.html('edit').off().on('click',_edit);
			$(this).off();
		}
	}

	$('#change-password').on('click',_show_pw_field);
	
	function _show_pw_field(e){
		e.stopPropagation();

		ui_focusout();
		$('#pw-field').find('.warning').html('');

		var pw_field = $('#pw-field');	
		var pw_block = $('#pw-block');
		if(pw_field.hasClass('hide')){
			pw_field.removeClass('hide');
			pw_block.on('click',function(e){ e.stopPropagation(); });
			$('#change-password').html('submit').off().on('click',_submit_pw);
		}
	}	
	function _submit_pw(){
		var pw_field = $('#pw-field');	
		var data = {};

		data.field = 'pw';
		data.val = pw_field.find('input[name=pw]').val();

		var confirm_pw = pw_field.find('input[name=confirm]').val();

		if(data.val != confirm_pw){
			pw_field.find('.warning').html('Your password and confirmation password do no match.');	
			pw_field.find('input').val('');
			return;
		}

		$.ajax({
			type: 'POST',
			url: '/index.php/user/edit_user',
			data: data, 
			dataType: 'json',
			error: function(){
				_hide_pw_field();
			},
		})
		.done(function(j){
			if(j.status){
				alert('修改成功!');
				_hide_pw_field();
			}else{
				pw_field.find('.warning').html(j.msg);	
				pw_field.find('input').val('');
				
			}
		});
	}
	function _hide_pw_field(){
		var pw_field = $('#pw-field');	
		var pw_block = $('#pw-block');
		if(!pw_field.hasClass('hide')){
			pw_field.addClass('hide');
			pw_field.find('input').val('');
			pw_block.off();
			$('#change-password').html('change password').off().on('click',_show_pw_field);
		}
	}
});

