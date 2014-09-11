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
						<th>Pages</th>
						<?php if(isset($user) && isset($allow_delete_user)): ?>
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
						<td><a href="<?= site_url('user/page/'.$e['name']) ?>"><?= isset($e['name']) ? $e['name'] : '' ?></a></td>
						<td><?= isset($e['realname']) ? $e['realname'] : '' ?></td>
						<td><?= isset($e['email']) ? $e['email'] : '' ?></td>
						<td><?= isset($e['phone']) ? $e['phone'] : '' ?></td>
						<td><?= isset($e['pages']) ? $e['pages'] : '' ?></td>
						<?php if(isset($user) && isset($allow_delete_user)): ?>
						<td><a class="button" name="<?= $e['name'] ?>" href="javascript:void(0)">del</a></td>
						<?php endif; ?>
					</tr>	
					<?php endforeach; ?> 
					<?php endif; ?>
				</tbody>
			</table>
			<?php else: ?>
			<p>您尚未登入!</p>
			<?php endif; ?>
		</article>
	</section>
