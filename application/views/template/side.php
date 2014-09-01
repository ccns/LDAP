	<aside class="sidebar">
		<ul>	
			<li>
				<h4>使用者</h4>
				<?php if(isset($user)): ?>
				<div id="sign_out">
					<ul>
						<li><span>Hi, <?= $user['name'] ?>.</span></li>
						<li><a id="sign_out_submit" href="javascript:void(0)" class="button">Sign Out</a></li>
					</ul>
				</div>
				<?php else: ?>
				<div id="sign_in">
					<form method="post" action="#" >
					<fieldset>
					<ul>
						<li><label class="label60" for="name">NAME</label><input type="text" size="16" value="" name="name"></li>
						<li><label class="label60" for="pw">PW</label><input type="password" size="16" value="" name="pw"></li>
						<li><span id="sign_in_msg" class="warning"></span></li>	
						<li><a id="sign_in_submit" href="javascript:void(0)" class="button">Sign In</a></li>
					</ul>
					</fieldset>
					</form>
				</div>
				<?php endif; ?>
			</li>

			<li>
				<h4>關於我們</h4>
				<ul>
				<li class="text">
				<p style="margin: 0;"></p>
				</li>
				</ul>
			</li>

			<li>
				<h4>搜尋</h4>
				<ul>
				<li class="text">
				<form method="get" class="searchform" action="#" >
				<p>
				<input type="text" size="32" value="" name="s" class="s" />

				</p>
				</form>	
				</li>
				</ul>
			</li>

			<li>
				<h4>好用連結</h4>
				<ul>
				<li><a href="#" title="CCNS Facebook">CCNS FACEBOOK</a></li>
				<li><a href="#" title="夢之大地">夢之大地</a></li>
				</ul>
			</li>

		</ul>

	</aside>
	<div class="clear"></div>
	</div>
	<!-- end of #body -->
