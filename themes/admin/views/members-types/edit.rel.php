<form class="form-horizontal col-lg-12 col-xs-no-padding" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10 col-xs-no-padding">
		<legend><?php echo $clauses->get($create ? 'create_members_type' : 'edit_members_type'); ?></legend>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="name"><?php echo $clauses->get('name'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="name" id="name" type="text" class="form-control" value="<?php if (!$create) echo $type['name']; ?>" required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="name"><?php echo $clauses->get('rights'); ?></label>
			<div class="col-md-4 col-xs-9">
<?php
				foreach ($rightsArray as $key => $value) {
?>
					<div class="checkbox">
						<label for="<?php echo $key; ?>" data-toggle="tooltip" data-placement="right" title="<?php echo $clauses->get($key . '_descr'); ?>">
							<input type="checkbox" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="on"<?php if (!$create AND $value) echo ' checked'; ?>>
							<?php echo $clauses->get($key); ?>
						</label>
					</div>
<?php
				}
?>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-offset-4 col-xs-offset-3" style="padding-left: 15px;">
				<button class="btn btn-primary"><?php echo $clauses->get('send'); ?></button>
			</div>
		</div>
	</fieldset>
</form>
