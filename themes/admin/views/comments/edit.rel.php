<form class="form-horizontal col-lg-12" method="post" action="">
	<fieldset class="col-lg-offset-1 col-lg-10">
		<legend><?php echo $clauses->get('edit_comment'); ?></legend>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="content"><?php echo $clauses->get('content'); ?></label>
			<div class="col-xs-8">
				<textarea id="content" name="content" class="form-control" rows="15" required><?php echo $comment['content']; ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="date"><?php echo $clauses->get('date'); ?></label>
			<div class="col-xs-4">
				<span class="form-control" disabled><?php echo Basics\Templates::dateTime($comment['date'], $comment['time']); ?></span>
			</div>
		</div>

		<div class="form-group">
			<label class="col-xs-4 control-label" for="hidden"><?php echo $clauses->get('hidden'); ?></label>
			<div class="col-xs-4">
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
			<div class="col-xs-offset-4" style="padding-left: 15px;">
				<button class="btn btn-primary"><?php echo $clauses->get('send'); ?></button>
			</div>
		</div>
	</fieldset>
</form>