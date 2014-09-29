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

		if(userdata.pw != confirm_pw){
			$('#add-user-msg').html('Your password and confirmation password do no match.');	
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
		
		var text = view_text.html();
		$(this).parent().find('.urls').each(function(){
			text = '';
			$(this).find('.split').each(function(){
				text += $(this).html()+'\n';
			});
		});
		text = text.replace(/\n*$/g,'');

		if(!view_text.hasClass('hide')){
			view_text.addClass('hide');
		}
		if(edit_text.hasClass('hide')){
			edit_text.val(text);
			edit_text.removeClass('hide');
			edit_text.focus();
		}

		p.on('click',function(e){ e.stopPropagation(); });
		$(this).html('submit').off().on('click',_submit_edit);
	}	
	function _submit_edit(e){
		var obj = $(this);

		var data = {};
		data.field = obj.parent().find('.edit-text').attr('name'); 
		data.val = obj.parent().find('.edit-text').val();
		data.name = $('#username').html();

		var view_text = obj.parent().find('.view-text');
		var p = obj.parent();

		data.val = data.val.replace(/\n*$/g,'');

		$.ajax({
			type: 'POST',
			url: '/index.php/user/edit_user',
			data: data, 
			dataType: 'json',
			error: function(x,st,err){
				p.each(_recover_edit);
			},
		})
		.done(function(j){
			if(j.status){
				var text = data.val;
				obj.parent().find('.urls').each(function(){
					text = _split_urls(data.val);
				});
				view_text.html(text);
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
		data.name = $('#username').html();

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
	
	$('.urls').each(function(){
		$(this).html(_split_urls($(this).html()));
	});
	
	function _split_urls(urls){
		if(!urls){
			return;
		}
		var split = urls.split(/\n|;|,| /);	
		var list = $('<ul class="no-style"></ul>');
		split.forEach(function(e){ 
			var link = e;
			var a = $('<a class="split" target="_blank"></a>');

			if(!e.match(/^https?:\/\//)){
				link = '//' + e;
			}
			a.html(e);
			a.attr('href',link);
			
			list.append(
				$('<li></li>').html(a)
			);
		});
		return list;
	}
});

