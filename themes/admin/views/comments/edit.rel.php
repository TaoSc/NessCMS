<form class="form-horizontal col-lg-12 col-xs-no-padding" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10 col-xs-no-padding">
		<legend><?php echo $clauses->get('edit_comment'); ?></legend>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="content"><?php echo $clauses->get('content'); ?></label>
			<div class="col-md-8 col-xs-9">
				<textarea id="content" name="content" class="form-control" rows="15" placeholder="<?php echo $clauses->get('bbcode_placeholder'); ?>" required><?php echo $comment['content']; ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="date"><?php echo $clauses->get('date'); ?></label>
			<div class="col-md-4 col-xs-9">
				<span class="form-control" disabled><?php Basics\Templates::dateTime($comment['date'], $comment['time']); ?></span>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 col-xs-3 control-label" for="hidden"><?php echo $clauses->get('visibility'); ?></label>
			<div class="col-md-4 col-xs-9">
				<select id="hidden" name="hidden" class="form-control">
<?php
					foreach ($hideOptions as $option) {
						echo '<option value="' .  $option['id'] . '"';
						if ((int) $comment['hidden'] === $option['id']) echo ' selected';
						echo '>' .  $clauses->get($option['name']) . '</option>' . PHP_EOL;
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
