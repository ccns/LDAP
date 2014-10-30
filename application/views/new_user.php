	<div id="body" class="width">
	<section id="content">
		<article>
			<h2>新增使用者</h2>
			<div class="article-info"></div>
			<form action="#" id="add-user" method="POST">
			<fieldset class="clear">
				<ul class="list-in-form">
				<li><label class="label150" for="name">*NAME</label><input type="text" class="add-text" name="name" maxlength="16" /><br /><span class="span150">&nbsp;</span><span>(3 - 16 characters)</span></li>
				<li><label class="label150" for="realname">REAL NAME</label><input type="text" class="add-text" name="realname" maxlength="16" /></li>
				<li><label class="label150" for="pw">*PASSWORD</label><input type="password" class="add-text" name="pw" maxlength="16" /><br /><span class="span150"></span><span>(6 - 16 characters)</span></li>
				<li><label class="label150" for="confirm">*CONFIRM</label><input type="password" class="add-text" name="confirm" maxlength="16" /></li>
				<li><label class="label150" for="email">*EMAIL</label><input type="text" class="add-text" name="email" maxlength="64" /></li>
				<li><label class="label150" for="phone">PHONE</label><input type="text" class="add-text" name="phone" maxlength="20" /></li>
				<li class="vert-align-mid"><label class="label150" for="PAGES">PAGES</label><textarea rows="4" cols="22" class="add-text" name="pages" maxlength="64"></textarea></li>
				<li><label class="label150" for="priv">PRIVILEGE</label><input type="radio" class="radio-style" name="priv" value="user" checked/>USER <input type="radio" class="radio-style" name="priv" value="admin" />ADMIN</li>
				<li><span id="add-user-msg" class="warning"></span></li>
				</ul>
				<a id="add-user-submit" class="button" >ADD</a>
			</fieldset>
			</form>
		</article>
	</section>
