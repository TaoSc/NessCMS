<dl class="poll-template" data-poll-id="<?php echo $poll['id']; ?>">
<?php
	foreach ($poll['answers'] as $answerLoop) {
?>
		<dt>
<?php
			if ($poll['already_voted'])
				echo $answerLoop['name'];
			else {
?>
				<label for="poll_radios-<?php echo $answerLoop['id']; ?>">
					<input type="radio" name="poll_radios" id="poll_radios-<?php echo $answerLoop['id']; ?>" value="<?php echo $answerLoop['id']; ?>">
					<?php echo $answerLoop['name']; ?>
				</label>
<?php
			}
?>
			<small class="pull-right"><?php echo Basics\Strings::plural($clauses->get('votes'), $answerLoop['votes']); ?></small>
		</dt>
		<dd>
			<div class="progress">
				<div class="progress-bar progress-bar-<?php echo $answerLoop['class'] . '" style="width: ' . $answerLoop['votes_percents']; ?>%"></div>
			</div>
		</dd>
<?php
	}
?>
</dl>
