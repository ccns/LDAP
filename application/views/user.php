	<div id="body" class="width">
	<section id="content">
		<article>
			<h2>個人資料</h2>
			<div class="article-info"></div>
	
			<form action="#" id="edit_user" method="POST">
			<fieldset class="clear">
				<p><span class="span120">NAME</span><span class="u-text"><?= isset($user['name']) ? $user['name'] : '&nbsp;' ?> </span></p>
				<p><span class="span120">REAL NAME</span><span class="u-text"><?= isset($user['realname']) ? $user['realname'] : '&nbsp;' ?> </span></p>
				<p class="vert-align-mid"><span class="span120">EMAIL</span><span class="u-text"><?= isset($user['email']) ? $user['email'] : '&nbsp;' ?> </span></p>
				<p><span class="span120">PHONE</span><span class="u-text"><?= isset($user['phone']) ? $user['phone'] : '&nbsp;' ?> </span></p>
				<p class="vert-align-mid"><span class="span120">PAGES</span><span class="u-text"><?= isset($user['pages']) ? $user['pages'] : '&nbsp;' ?> </span></p>
			</fieldset>
			</form>


		</article>
		<?php if(isset($user['priv']) && $user['priv'] == 1): ?>
		<article>
			<h2>新增使用者</h2>
			<p>
				<form action="#" id="add_user" method="POST">
				<fieldset class="clear">
					<p><label class="label120" for="name">*NAME</label><input type="text" name="name" maxlength=16 /></p>
					<p><label class="label120" for="realname">REAL NAME</label><input type="text" name="realname" maxlength=64 /></p>
					<p><label class="label120" for="pw">*PASSWORD</label><input type="password" name="pw" maxlength=16 /></p>
					<p><label class="label120" for="confirm_pw">*CONFIRM</label><input type="password" name="confirm_pw" maxlength=16 /></p>
					<p><label class="label120" for="email">*EMAIL</label><input type="text" name="email" maxlength=64 /></p>
					<p><label class="label120" for="phone">PHONE</label><input type="text" name="phone" maxlength=20 /></p>
					<p class="vert-align-mid"><label class="label120" for="PAGES">PAGES</label><textarea rows="4" cols="22" name="pages"></textarea></p>
					<p><label class="label120" for="priv">PRIViLEGE</label><input type="radio" name="user" value="1" checked/>USER <input type="radio" name="admin" value="1" />ADMIN</p>
					<p><label class="label120"></label><a id="adduser_submit" class="button" >ADD</a></p>
					<!--ul class="no_style">
					<li><label class="label120" for="name">*NAME</label><input type="text" name="name" maxlength=16 /></li>
					<li><label class="label120" for="realname">REAL NAME</label><input type="text" name="realname" maxlength=64 /></li>
					<li><label class="label120" for="pw">*PASSWORD</label><input type="password" name="pw" maxlength=16 /></li>
					<li><label class="label120" for="confirm_pw">*CONFIRM</label><input type="password" name="confirm_pw" maxlength=16 /></li>
					<li><label class="label120" for="email">*EMAIL</label><input type="text" name="email" maxlength=64 /></li>
					<li><label class="label120" for="phone">PHONE</label><input type="text" name="phone" maxlength=20 /></li>
					<li class="vert-align-mid"><label class="label120" for="PAGES">PAGES</label><textarea rows="4" cols="22" name="pages"></textarea></li>
					<li><label class="label120" for="priv">PRIVELEGE</label><input type="radio" name="user" value="1" checked/>USER <input type="radio" name="admin" value="1" />ADMIN</li>
					<li><label class="label120"></label><a id="adduser_submit" class="button" >ADD</a></li>
					</ul-->
				</fieldset>
				</form>
			</p>
		</article>
		<?php endif; ?>
	</section>
