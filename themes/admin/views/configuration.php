<form class="form-horizontal col-lg-12" method="post" action="">
	<fieldset>
		<legend><?php echo $clauses->get('system_conf'); ?></legend>

		<div class="col-lg-offset-1 col-lg-10">
			<div class="form-group">
				<label class="col-xs-4 control-label" for="anonymous_coms"><?php echo $clauses->get('anonymous_coms'); ?></label>
				<div class="col-xs-4">
					<div class="checkbox">
						<label for="anonymous_coms">
							<input type="checkbox" name="anonymous_coms" id="anonymous_coms" value="on"<?php if (Basics\Site::parameter('anonymous_coms')) echo ' checked'; ?>>
							<?php echo $clauses->get('enable'); ?>
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-4 control-label" for="anonymous_votes"><?php echo $clauses->get('anonymous_votes'); ?></label>
				<div class="col-xs-4">
					<div class="checkbox">
						<label for="anonymous_votes">
							<input type="checkbox" name="anonymous_votes" id="anonymous_votes" value="on"<?php if (Basics\Site::parameter('anonymous_votes')) echo 'checked'; ?>>
							<?php echo $clauses->get('enable'); ?>
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-4 control-label" for="name"><?php echo $clauses->get('site_name'); ?></label>
				<div class="col-xs-4">
					<input name="name" id="name" type="text" class="form-control" value="<?php echo $siteName; ?>" required>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-4 control-label" for="private_emails"><?php echo $clauses->get('private_emails'); ?></label>
				<div class="col-xs-4">
					<div class="checkbox">
						<label for="private_emails">
							<input type="checkbox" name="private_emails" id="private_emails" value="on"<?php if (Basics\Site::parameter('private_emails')) echo 'checked'; ?>>
							<?php echo $clauses->get('enable'); ?>
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-xs-4 control-label" for="url_rewriting"><?php echo $clauses->get('url_rewriting'); ?></label>
				<div class="col-xs-4">
					<div class="checkbox">
						<label for="url_rewriting">
							<input type="checkbox" name="url_rewriting" id="url_rewriting" value="on"<?php if (Basics\Site::parameter('url_rewriting')) echo 'checked'; ?>>
							<?php echo $clauses->get('enable'); ?>
						</label>
					</div>
				</div>
			</div>

			<div class="pull-right form-group">
				<button class="btn btn-primary"><?php echo $clauses->get('send'); ?></button>
			</div>
		</div>
	</fieldset>
</form>