<div class="row">
	<div class="col-lg-12">
		<div class="page-header no-margin">
			<img data-original="<?php echo Basics\Templates::getImg('avatars/' . $member['avatar']['slug'], $member['avatar']['format'], 100, 100); ?>" class="img-circle pull-left member-avatar" alt="<?php echo $clauses->get('avatar'); ?>">
			<h1><?= $member['nickname']; ?></h1>
<?php
				if (!Basics\Site::parameter('private_emails') AND $member['email'])
					echo '<a href="mailto:' . $member['email'] . '" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-envelope"></span> ' . $clauses->get('send_message') . '</a>';
				if ($currentMemberId === $member['id'] OR $rights['admin_access'])
					echo '<a href="' . $linksDir . 'admin/members/' . $member['id'] . '" class="pull-right btn btn-link"><span class="glyphicon glyphicon-pencil"></span> ' . $clauses->get('edit_profile') . '</a>';
?>

			<div class="clearfix"></div>
		</div>

		<dl class="dl-horizontal">
			<dt><?php echo $clauses->get('reg_date'); ?></dt>
			<dd><?php Basics\Templates::dateTime($member['registration']['date'], $member['registration']['time']); ?></dd>
<?php
			if ($member['name']) {
?>
				<dt><?php echo $clauses->get('name'); ?></dt>
				<dd><?php echo $member['name']; ?></dd>
<?php
			}

			if ($member['birth']) {
?>
				<dt><?php echo $clauses->get('age'); ?></dt>
				<dd><?php echo Basics\Dates::age($member['birth']) . ' ' . $clauses->get('years_old'); ?></dd>
<?php
			}

			if (!Basics\Site::parameter('private_emails') AND $member['email']) {
?>
				<dt><?php echo $clauses->get('email'); ?></dt>
				<dd><?php echo $member['email']; ?></dd>
<?php
			}
?>
			<dt><?php echo $clauses->get('type'); ?></dt>
			<dd><a href="<?php echo $linksDir . 'members/types/' . $member['type']['slug']; ?>"><?php echo $member['type']['name']; ?></a></dd>
		</dl>
	</div>
</div>
