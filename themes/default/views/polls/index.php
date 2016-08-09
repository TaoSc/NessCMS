<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			<h1><?php echo $clauses->get('polls'); ?></h1>
		</div>

<?php
		if ($pollsList)
			Basics\Templates::textList($pollsList);
		else
			echo $clauses->get('no_polls');
?>
		<div class="bottom-link">
			<a href="<?php echo $linksDir . $createPollLink; ?>">» <?php echo $clauses->get('create_poll'); ?></a>
		</div>
	</div>
</div>
