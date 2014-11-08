	<div id="body" class="width">
	<section id="content">
		<article>
			<h2>個人資訊</h2>
			<div class="article-info"></div>
	
			<?php if(isset($user)): ?>
			<?php 
				foreach( array('name','realname','email','phone','pages') as $n){
					if(!isset($user[$n])){
						$user[$n] = '';
					}
					if(!isset($view[$n])){
						$view[$n] = '';
					}
				}
			?>
			<form action="#" id="edit-user" method="POST">
			<fieldset class="clear">
				<ul class="list-in-form">
				<li><div class="edit-block"><span class="span150">NAME</span><span id="username" class="view-text"><?= $view['name'] ?></span>
				</div></li>
				<li><div class="edit-block"><span class="span150">REAL NAME</span><span class="view-text"><?= $view['realname'] ?></span>
				<?php if(isset($local_view) || isset($allow_edit_user)): ?>
					<input class="edit-text hide" name="realname" value="" maxlength="16"/>
					<a class="button edit-user" href="javascript:void(0)">edit</a>
					<span class="warning"></span>
				<?php endif; ?>
				</div></li>
				<li><div class="vert-align-mid edit-block"><span class="span150">EMAIL</span><span class="view-text"><?= $view['email'] ?></span>
				<?php if(isset($local_view) || isset($allow_edit_user)): ?>
					<input class="edit-text hide" name="email" value="" maxlength="64"/>
					<a class="button edit-user" href="javascript:void(0)">edit</a>
					<span class="warning"></span>
				<?php endif; ?>
				</div></li>
				<li><div class="edit-block"><span class="span150">PHONE</span><span class="view-text"><?= $view['phone'] ?></span>
				<?php if(isset($local_view) || isset($allow_edit_user)): ?>
					<input class="edit-text hide" name="phone" value="" maxlength="20"/>
					<a class="button edit-user" href="javascript:void(0)">edit</a>
					<span class="warning"></span>
				<?php endif; ?>
				</div></li>
				<li><div class="vert-align-mid edit-block"><span class="span150">PAGES</span><span class="view-text urls"><?= $view['pages'] ?></span>
				<?php if(isset($local_view) || isset($allow_edit_user)): ?>
					<textarea rows = "4" cols="22" class="edit-text hide" name="pages" value="" maxlength="512"></textarea>
					<a class="button edit-user" href="javascript:void(0)">edit</a>
					<span class="warning"></span>
					<br /><span class="tip hide"><span class="span150">&nbsp;</span><span>(e.g. www.facebook.com/myname, www.mypage.com)</span></span>
				<?php endif; ?>
				</div></li>

				<?php if(isset($allow_edit_priv)): ?>
				<li><div class="edit-block"><span class="span150">PRIVILEGE</span><input type="radio" class="edit-priv radio-style" name="priv" value="user" <?= isset($view['user_priv']) ? 'checked' : '' ?> />USER <input type="radio" class="edit-priv radio-style" name="priv" value="admin" <?= isset($view['admin_priv']) ? 'checked' : '' ?> />ADMIN</div></li>
				<?php endif; ?>

				<?php if(isset($local_view) && isset($allow_edit_user)): ?>
				<li><div class="edit-block"><span class="span150">PRIVILEGE</span>ADMIN</div></li>
				
				<?php endif; ?>
				</ul>
				<?php if(isset($local_view) || isset($allow_edit_user)): ?>
				<div id="pw-block">
				<ul id="pw-field" class="list-in-form hide">
				<li><span class="span150">NEW PASSWORD</span><input type="password" name="pw" class="pw-text" maxlength="16"/><br /><span class="span150">&nbsp;</span><span>(6 ~ 16 characters)</span></li>
				<li><span class="span150">CONFIRM</span><input type="password" name="confirm" class="pw-text" maxlength="16"/></li>
				<li><span class="warning"></span></li>
				</ul>
				<ul class="list-in-form">
				<li><div><span Class="span150">&nbsp</span><a id="change-password" class="button" href="javascript:void(0)">change password</a></div></li>
				</ul>
				</div>
				<?php endif; ?>
			</fieldset>
			</form>
			<?php else: ?>
			<p></p>
			<div class="no-signin"><span>您尚未登入!</span></div>
			<?php endif; ?>

		</article>
	</section>
