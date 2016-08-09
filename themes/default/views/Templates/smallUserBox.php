<a class="small-user-box" href="<?php echo $linksDir . 'members/' . $member['slug'] . '/'; ?>">
	<div class="small-user-box no-padding <?php echo $size; ?>">
		<img data-original="<?php echo Basics\Templates::getImg('avatars/' . $member['avatar_slug'], $member['avatar'], 100, 100); ?>" alt="<?php echo $clauses->get('avatar'); ?>">
		<h4 class="user-name"><?php echo $member['nickname']; ?></h4>
		<span class="user-infos">
<?php
			echo Basics\Strings::plural($member['type']['name'], false) . ' · ' . $clauses->get('registered') . ' ' .
				 Basics\Dates::relativeTime($member['registration']['date'], $member['registration']['time']);
?>
		</span>
	</div>
</a>
