<form class="form-horizontal col-lg-12 col-xs-no-padding" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10 col-xs-no-padding">
		<legend><?php echo $clauses->get($create ? 'create_tag' : 'edit_tag'); ?></legend>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="name"><?php echo $clauses->get('name'); ?></label>
			<div class="col-md-4 col-xs-9">
				<input name="name" id="name" type="text" class="form-control" value="<?php if (!$create) echo $tag['name']; ?>" required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="type"><?php echo $clauses->get('type'); ?></label>
			<div class="col-md-4 col-xs-9">
				<select id="type" name="type" class="form-control">
<?php
					foreach ($tagsTypes as $tagType) {
						echo '<option value="' .  $tagType . '"';
						if (!$create AND $tag['type'] === $tagType) echo ' selected';
						echo '>' .  $clauses->get($tagType) . '</option>' . PHP_EOL;
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
