	<div id="body" class="width">
	<section id="content">
		<article>
			<h2>個人資料</h2>
			<div class="article-info"></div>
	
			<?php if(isset($user)): ?>
			<form action="#" id="edit_user" method="POST">
			<fieldset class="clear">
				<ul class="list_in_form">
				<li><div><span class="span120">NAME</span><span class="u-text"><?= isset($user['name']) ? $user['name'] : '&nbsp;' ?> </span>
				<?php if(isset($local_view)): ?>
					<a class="button" href="javascript:void(0)">edit</a>
				<?php endif; ?>
				</div></li>
				<li><div><span class="span120">REAL NAME</span><span class="u-text"><?= isset($user['realname']) ? $user['realname'] : '&nbsp;' ?> </span>
				<?php if(isset($local_view)): ?>
					<a class="button" href="javascript:void(0)">edit</a>
				<?php endif; ?>
				</div></li>
				<li><div class="vert-align-mid"><span class="span120">EMAIL</span><span class="u-text"><?= isset($user['email']) ? $user['email'] : '&nbsp;' ?> </span>
				<?php if(isset($local_view)): ?>
					<a class="button" href="javascript:void(0)">edit</a>
				<?php endif; ?>
				</div></li>
				<li><div><span class="span120">PHONE</span><span class="u-text"><?= isset($user['phone']) ? $user['phone'] : '&nbsp;' ?> </span>
				<?php if(isset($local_view)): ?>
					<a class="button" href="javascript:void(0)">edit</a>
				<?php endif; ?>
				</div></li>
				<li><div class="vert-align-mid"><span class="span120">PAGES</span><span class="u-text"><?= isset($user['pages']) ? $user['pages'] : '&nbsp;' ?> </span>
				<?php if(isset($local_view)): ?>
					<a class="button" href="javascript:void(0)">edit</a>
				<?php endif; ?>
				</div></li>
				</ul>
			</fieldset>
			</form>
			<?php else: ?>
			<p>您尚未登入!</p>
			<?php endif; ?>

		</article>
		<?php if(isset($user) && isset($allow_add_user)):?>
		<article>
			<h2>新增使用者</h2>
			<p>
				<form action="#" id="add_user" method="POST">
				<fieldset class="clear">
					<ul class="list_in_form">
					<li><label class="label120" for="name">*NAME</label><input type="text" name="name" maxlength=16 /><br /><span class="span120">&nbsp;</span><span>(4 - 16 letters)</span></li>
					<li><label class="label120" for="realname">REAL NAME</label><input type="text" name="realname" maxlength=16 /></li>
					<li><label class="label120" for="pw">*PASSWORD</label><input type="password" name="pw" maxlength=16 /><br /><span class="span120"></span><span>(6 - 16 letters)</span></li>
					<li><label class="label120" for="confirm">*CONFIRM</label><input type="password" name="confirm" maxlength=16 /></li>
					<li><label class="label120" for="email">*EMAIL</label><input type="text" name="email" maxlength=64 /></li>
					<li><label class="label120" for="phone">PHONE</label><input type="text" name="phone" maxlength=20 /></li>
					<li class="vert-align-mid"><label class="label120" for="PAGES">PAGES</label><textarea rows="4" cols="22" name="pages"></textarea></li>
					<li><label class="label120" for="priv">PRIVILEGE</label><input type="radio" name="user" value="1" checked/>USER <input type="radio" name="admin" value="1" />ADMIN</li>
					<li><span id="add_user_msg" class="warning">&nbsp;</span></li>
					</ul>
					<a id="add_user_submit" class="button" >ADD</a>
				</fieldset>
				</form>
			</p>
		</article>
		<?php endif; ?>
	</section>
