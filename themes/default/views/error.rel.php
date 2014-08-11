<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			<h1><?php echo $clauses->get('error'); ?></h1>
		</div>

		<p><?php echo $errorMsg; ?></p>
<?php
		if ($showHomeBtn === true) {
?>
			<a href="<?php echo $linksDir; ?>index" class="btn btn-large btn-info" style="margin-bottom: 10px;">
				<span class="glyphicon glyphicon-home"></span> <?php echo $clauses->get('error_home_btn'); ?>
			</a>
<?php
		}
?>
	</div>
</div>