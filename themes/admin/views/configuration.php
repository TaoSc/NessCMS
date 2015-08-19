<form class="form-horizontal col-lg-12 col-xs-no-padding" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10 col-xs-no-padding">
		<legend><?php echo $clauses->get('system_conf'); ?></legend>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="coms_per_page"><?php echo $clauses->get('coms_per_page'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="coms_per_page" id="coms_per_page" type="number" class="form-control" value="<?php echo Basics\Site::parameter('coms_per_page'); ?>" required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="anonymous_coms"><?php echo $clauses->get('anonymous_coms'); ?></label>
			<div class="col-md-4 col-xs-9">
				<div class="checkbox">
					<label for="anonymous_coms">
						<input type="checkbox" name="anonymous_coms" id="anonymous_coms" value="on"<?php if (Basics\Site::parameter('anonymous_coms')) echo ' checked'; ?>>
						<?php echo $clauses->get('enable'); ?>
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="anonymous_votes"><?php echo $clauses->get('anonymous_votes'); ?></label>
			<div class="col-md-4 col-xs-9">
				<div class="checkbox">
					<label for="anonymous_votes">
						<input type="checkbox" name="anonymous_votes" id="anonymous_votes" value="on"<?php if (Basics\Site::parameter('anonymous_votes')) echo ' checked'; ?>>
						<?php echo $clauses->get('enable'); ?>
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="default_language"><?php echo $clauses->get('default_language'); ?></label>
			<div class="col-md-4 col-xs-9">
				<select id="default_language" name="default_language" class="form-control">
<?php
					foreach ($languages as $languageLoop) {
						echo '<option value="' .  $languageLoop['code'] . '"';
						if (Basics\Site::parameter('default_language') === $languageLoop['code'])
							echo ' selected';
						echo '>' .  $languageLoop['name'] . '</option>' . PHP_EOL;
					}
?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="name"><?php echo $clauses->get('site_name'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="name" id="name" type="text" class="form-control" value="<?php echo $siteName; ?>" required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="cache_enabled"><?php echo $clauses->get('cache_enabled'); ?></label>
			<div class="col-md-4 col-xs-9">
				<div class="checkbox">
					<label for="cache_enabled">
						<input type="checkbox" name="cache_enabled" id="cache_enabled" value="on"<?php if (Basics\Site::parameter('cache_enabled')) echo ' checked'; ?>>
						<?php echo $clauses->get('enable'); ?>
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="private_emails"><?php echo $clauses->get('private_emails'); ?></label>
			<div class="col-md-4 col-xs-9">
				<div class="checkbox">
					<label for="private_emails">
						<input type="checkbox" name="private_emails" id="private_emails" value="on"<?php if (Basics\Site::parameter('private_emails')) echo ' checked'; ?>>
						<?php echo $clauses->get('enable'); ?>
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="url_rewriting"><?php echo $clauses->get('url_rewriting'); ?></label>
			<div class="col-md-4 col-xs-9">
				<div class="checkbox">
					<label for="url_rewriting">
						<input type="checkbox" name="url_rewriting" id="url_rewriting" value="on"<?php if (Basics\Site::parameter('url_rewriting')) echo ' checked'; ?>>
						<?php echo $clauses->get('enable'); ?>
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="default_users_type"><?php echo $clauses->get('default_users_type'); ?></label>
			<div class="col-md-4 col-xs-9">
				<select id="default_users_type" name="default_users_type" class="form-control">
<?php
					foreach ($membersTypes as $typeLoop) {
						echo '<option value="' .  $typeLoop['id'] . '"';
						if (Basics\Site::parameter('default_users_type') === $typeLoop['id'])
							echo ' selected';
						echo '>' .  $typeLoop['name'] . '</option>' . PHP_EOL;
					}
?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-offset-4 col-xs-offset-3" style="padding-left: 15px;">
				<button class="btn btn-primary"><?php echo $clauses->get('send'); ?></button>
			</div>
		</div>
	</fieldset>
</form>