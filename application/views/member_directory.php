    <div id="body" class="width">
	<section id="content">
	    <article>
			<h2>通訊錄</h2>
			<div class="article-info"></div>

			<?php if(isset($user)): ?>
			<table id="member-directory">
				<thead>
					<tr>
						<th>Name</th>
						<th>Real Name</th>
						<th>Email</th>
						<th>Phone</th>
						<?php if(isset($user) && isset($allow_edit_user)): ?>
						<th>&nbsp;</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($list)): ?>
					<?php foreach($list as $e): ?>
					<?php 
						if(!isset($e['name'])){
							$e['name'] = '';
						}
					?>
					<tr>
						<td><a href="/index.php/user/page/<?= $e['name'] ?>"><?= $e['name'] ?></a></td>
						<td><?= isset($e['realname']) ? $e['realname'] : '' ?></td>
						<td><?= isset($e['email']) ? $e['email'] : '' ?></td>
						<td><?= isset($e['phone']) ? $e['phone'] : '' ?></td>
						<?php if(isset($user) && isset($allow_edit_user)): ?>
						<td><a class="button del" name="<?= isset($e['uid']) ? $e['uid'] : '' ?>" rel="<?= $e['name'] ?>" href="javascript:void(0)">del</a></td>
						<?php endif; ?>
					</tr>	
					<?php endforeach; ?> 
					<?php endif; ?>
				</tbody>
			</table>
			<?php else: ?>
			<p></p>
			<div class="no-signin"><span>您尚未登入!</span></div>
			<?php endif; ?>
		</article>
	</section>
