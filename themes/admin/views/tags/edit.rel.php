<form class="form-horizontal col-lg-12" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10">
		<legend><?php echo $clauses->get($params[2] === '0' ? 'create_tag' : 'edit_tag'); ?></legend>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="name"><?php echo $clauses->get('name'); ?></label>
			<div class="col-xs-4">
				<input name="name" id="name" type="text" class="form-control" value="<?php if (!$create) echo $tag['name']; ?>" required>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="type"><?php echo $clauses->get('category'); ?></label>
			<div class="col-xs-4">
				<select id="type" name="type" class="form-control">
<?php
					foreach ($types as $tagTypeId => $tagTypeName) {
						echo '<option value="' .  $tagTypeId . '"';
						if (!$create AND $tag['type'] === $tagTypeId) echo ' selected';
						echo '>' .  $tagTypeName . '</option>' . PHP_EOL;
					}
?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<div class="col-xs-offset-4" style="padding-left: 15px;">
				<button class="btn btn-primary"><?php echo $clauses->get('send'); ?></button>
			</div>
		</div>
	</fieldset>
</form>