	<div id="body" class="width">
	<section id="content">
		<article>
			<h2>個人資料</h2>
			<div class="article-info"></div>
	
			<?php if(isset($user)): ?>
			<?php 
				foreach( array('name','realname','email','phone','pages') as $n){
					if(!isset($user[$n])){
						$user[$n] = '';
					}
				}
			?>
			<form action="#" id="edit-user" method="POST">
			<fieldset class="clear">
				<ul class="list-in-form">
				<li><div class="edit-block"><span class="span150">NAME</span><span class="view-text"><?= $user['name'] ?> </span>
				</div></li>
				<li><div class="edit-block"><span class="span150">REAL NAME</span><span class="view-text"><?= $user['realname'] ?> </span>
				<?php if(isset($local_view)): ?>
					<input class="edit-text hide" name="realname" value="<?= $user['realname'] ?>" />
					<a class="button edit-user" href="javascript:void(0)">edit</a>
				<?php endif; ?>
				</div></li>
				<li><div class="vert-align-mid edit-block"><span class="span150">EMAIL</span><span class="view-text"><?= $user['email'] ?> </span>
				<?php if(isset($local_view)): ?>
					<input class="edit-text hide" name="email" value="<?= $user['email'] ?>" />
					<a class="button edit-user" href="javascript:void(0)">edit</a>
				<?php endif; ?>
				</div></li>
				<li><div class="edit-block"><span class="span150">PHONE</span><span class="view-text"><?= $user['phone'] ?> </span>
				<?php if(isset($local_view)): ?>
					<input class="edit-text hide" name="phone" value="<?= $user['phone'] ?>" />
					<a class="button edit-user" href="javascript:void(0)">edit</a>
				<?php endif; ?>
				</div></li>
				<li><div class="vert-align-mid edit-block"><span class="span150">PAGES</span><span class="view-text"><?= $user['pages'] ?> </span>
				<?php if(isset($local_view)): ?>
					<input class="edit-text hide" name="pages" value="<?= $user['pages'] ?>" />
					<a class="button edit-user" href="javascript:void(0)">edit</a>
				<?php endif; ?>
				</div></li>
				</ul>
				<div id="pw-block">
				<ul id="pw-field" class="list-in-form hide">
				<li><span class="span150">NEW PASSWORD</span><input type="password" name="pw" class="pw-text" /></li>
				<li><span class="span150">CONFIRM</span><input type="password" name="confirm" class="pw-text" /></li>
				</ul>
				<ul class="list-in-form">
				<?php if(isset($local_view)): ?>
				<li><div><span Class="span150">&nbsp</span><a id="change-password" class="button" href="javascript:void(0)">change password</a></div></li>
				<?php endif; ?>
				</ul>
				</div>
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
				<form action="#" id="add-user" method="POST">
				<fieldset class="clear">
					<ul class="list-in-form">
					<li><label class="label150" for="name">*NAME</label><input type="text" name="name" maxlength=16 /><br /><span class="span150">&nbsp;</span><span>(4 - 16 letters)</span></li>
					<li><label class="label150" for="realname">REAL NAME</label><input type="text" name="realname" maxlength=16 /></li>
					<li><label class="label150" for="pw">*PASSWORD</label><input type="password" name="pw" maxlength=16 /><br /><span class="span150"></span><span>(6 - 16 letters)</span></li>
					<li><label class="label150" for="confirm">*CONFIRM</label><input type="password" name="confirm" maxlength=16 /></li>
					<li><label class="label150" for="email">*EMAIL</label><input type="text" name="email" maxlength=64 /></li>
					<li><label class="label150" for="phone">PHONE</label><input type="text" name="phone" maxlength=20 /></li>
					<li class="vert-align-mid"><label class="label150" for="PAGES">PAGES</label><textarea rows="4" cols="22" name="pages"></textarea></li>
					<li><label class="label150" for="priv">PRIVILEGE</label><input type="radio" name="user" value="1" checked/>USER <input type="radio" name="admin" value="1" />ADMIN</li>
					<li><span id="add-user-msg" class="warning">&nbsp;</span></li>
					</ul>
					<a id="add-user-submit" class="button" >ADD</a>
				</fieldset>
				</form>
			</p>
		</article>
		<?php endif; ?>
	</section>
