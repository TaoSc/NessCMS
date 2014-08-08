<div class="row">
	<div class="col-lg-12">
		<blockquote>
			<?php echo $poll['question']; ?>
			<small class="pull-right poll-participants"><?php echo Basics\Strings::plural($clauses->get('participants'), $poll['total_votes']); ?></small>
		</blockquote>

		<div class="row">
			<div class="col-lg-offset-2 col-lg-8">
				<?php Basics\Templates::smallUserBox($poll['author']); ?>
				<div class="col-sm-offset-2 col-sm-5">
					<span class="pull-right"><?php Basics\Templates::dateTime($poll['date'], $poll['time']); ?></span>
				</div>
			</div>
		</div>

		<hr>

		<?php Basics\Templates::pollAnswers($poll); ?>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 subtle-line-top">
		<?php Comments\Handling::view($poll['id'], 'polls'); ?>
	</div>
</div>