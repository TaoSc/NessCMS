<form class="form-horizontal col-lg-12 col-xs-no-padding" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10 col-xs-no-padding">
		<legend><?php echo $clauses->get('edit_profile'); ?></legend>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="nickname"><?php echo $clauses->get('nickname'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="nickname" id="nickname" type="text" class="form-control" value="<?php echo $member['nickname']; ?>" required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="avatar"><?php echo $clauses->get('avatar'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="avatar" id="avatar" type="url" class="form-control" placeholder="<?= $clauses->get('img_placeholder'); ?>">
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-offset-4 col-xs-offset-3" style="padding-left: 15px;">
				<button class="btn btn-primary"><?php echo $clauses->get('edit'); ?></button>
			</div>
		</div>
	</fieldset>
</form>
