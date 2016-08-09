<div class="row">
	<div class="col-xs-offset-<?php echo (1 + $comment['recursivity']) . ' col-xs-' . (10 - $comment['recursivity']); ?> no-padding single-comment<?php if ($commentAnswers AND $comment['recursivity'] === 0) echo ' less-margin'; ?>" id="comment-<?php echo $comment['id']; ?>">
		<div class="col-xs-2 no-padding user-box text-center">
			<a href="<?php echo $linksDir . 'members/' . $comment['author']['slug'] . '/'; ?>">
				<img data-original="<?php echo Basics\Templates::getImg('avatars/' . $comment['author']['avatar_slug'], $comment['author']['avatar'], 100, 100); ?>" alt="<?php echo $clauses->get('avatar'); ?>" class="img-circle img-responsive">
				<h4><?php echo $comment['author']['nickname']; ?></h4>
			</a>
		</div>

		<div class="col-xs-10 content-box">
			<div class="row infos-box">
				<div class="col-lg-12">
<?php
				Basics\Templates::dateTime($comment['date'], $comment['time']);

				if ($comment['modif_date']) {
					echo ' · ' . $clauses->get('last_modified');
					Basics\Templates::dateTime($comment['modif_date'], $comment['modif_time']);
				}

				if ($comment['language']['code'] !== $language)
					echo ' · <b>' . $clauses->get('comment_lang_info') . Basics\Strings::lcFirst($comment['language']['lang_name']) . '</b>';
?>
				</div>
			</div>

			<div class="row content-itself<?php if ($comment['hidden'] == 1) echo ' bg-warning'; ?>">
				<div class="col-lg-12">
					<div class="btn-group btn-group-xs pull-right">
<?php
						if ($comment['removal_cond'])
							echo '<a href="' . $linksDir . 'admin/comments/' . $comment['id'] . '/delete' . '" type="button" class="btn btn-warning">' . $clauses->get('delete') . '</a>';
						if ($comment['edit_cond'])
							echo '<a href="' . $linksDir . 'admin/comments/' . $comment['id'] . '' . '" type="button" class="btn btn-warning">' . $clauses->get('modify') . '</a>';
						if ($hasVoted AND $currentMemberId AND !$comment['hidden'])
							echo '<button type="button" class="btn btn-inverse vote-btn" data-id="' . $comment['id'] . '" data-type="comments" value="strip">' . $clauses->get('remove_vote') . '</button>';
?>
					</div>
<?php
					if ($comment['hidden'])
						echo '<span class="text-danger">' . $clauses->get('com_hidden_lvl1') . '</span>';
					else
						echo $comment['content'];
?>
				</div>
			</div>

			<div class="row options-box">
				<div class="col-xs-<?php if ($commentsTemplate AND (\Basics\Site::parameter('anonymous_coms') OR $currentMemberId)) echo '7'; else echo '12'; ?>">
					<div class="btn-group btn-group-justified">
						<div class="btn-group">
							<button type="button" class="btn btn-success btn-sm vote-btn"<?php if ($voteBtnsCond) echo ' disabled'; ?> data-id="<?php echo $comment['id']; ?>" data-type="comments" value="up">
								<span class="glyphicon glyphicon-thumbs-up"></span> <?php echo $clauses->get('to_like'); ?> 
								(<span class="votes-nbr"><?php echo $comment['likes']; ?></span>)
							</button>
						</div>

						<div class="btn-group">
							<button type="button" class="btn btn-danger btn-sm vote-btn"<?php if ($voteBtnsCond) echo ' disabled'; ?> data-id="<?php echo $comment['id']; ?>" data-type="comments" value="down">
								<span class="glyphicon glyphicon-thumbs-down"></span> <?php echo $clauses->get('to_dislike'); ?> 
								(<span class="votes-nbr"><?php echo $comment['dislikes']; ?></span>)								
							</button>
						</div>
					</div>
				</div>

<?php
				if ($commentsTemplate AND (\Basics\Site::parameter('anonymous_coms') OR $currentMemberId)) {
?>
					<div class="col-xs-5">
						<button type="button" class="btn btn-default btn-sm btn-block answer-btn" value="<?php echo $comment['id']; ?>">
							<span class="glyphicon glyphicon-share-alt"></span> <?php echo $clauses->get('answer'); ?>
						</button>
					</div>
<?php
				}
?>
			</div>
		</div>
	</div>

<?php
	if ($commentAnswers) {
		echo '<div class="replies container';
		if ($comment['recursivity'] !== 0)
			echo ' less-margin';
		echo '">';
			foreach ($commentAnswers as $commentLoop)
				Basics\Templates::comment($commentLoop, $languageCheck, $hidden, $commentsTemplate);
		echo '</div>';
	}
?>
</div>
