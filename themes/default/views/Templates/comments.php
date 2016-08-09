<div id="comments">
	<div class="row">
		<div class="col-sm-3">
			<h3><?php echo $clauses->get('comments'); ?> <span class="label label-default comments-nbr"><?php echo $allCommentsNbr;?></span></h3>
		</div>
		<div class="col-sm-9 comments-toolbox">
<?php
			if ($pages > 1) {
?>
				<ul class="pagination pagination-sm pull-left">
<?php
					echo '<li';
					if ($actualPage - 1 === 0)
						echo ' class="disabled"';
					echo '><a href="' . $linksDir . 'comments/' . $postType . '/' . $postId . '/' . ($actualPage - 1) . '/' . (int) $languageCheck . '/' . (int) $order . '">«</a></li>';

					for ($i = 1; $i <= $pages; $i++) {
						echo '<li';
						if ($actualPage == $i)
							echo ' class="active"';
						echo '><a href="' . $linksDir . 'comments/' . $postType . '/' . $postId . '/' . $i . '/' . (int) $languageCheck . '/' . (int) $order . '">' . $i . '</a></li>';
					}

					echo '<li';
					if ($actualPage + 1 > $pages)
						echo ' class="disabled"';
					echo '><a href="' . $linksDir . 'comments/' . $postType . '/' . $postId . '/' . ($actualPage + 1) . '/' . (int) $languageCheck . '/' . (int) $order . '">»</a></li>';
?>
				</ul>
<?php
			}
?>

			<div class="btn-group pull-left coms-dropdowns">
				<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo $clauses->get('lang_options'); ?> <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
					<li role="presentation" class="dropdown-header"><?php echo $clauses->get('coms_lang_header'); ?></li>
					<li><?php echo '<a href="' . $linksDir . 'comments/' . $postType . '/' . $postId . '/' . $actualPage . '/1/' . (int) $order . '">' . $clauses->get('coms_lang_op1') . '</a></li>'; ?>
					<li><?php echo '<a href="' . $linksDir . 'comments/' . $postType . '/' . $postId . '/' . $actualPage . '/0/' . (int) $order . '">' . $clauses->get('coms_lang_op2') . '</a></li>'; ?>
				</ul>
			</div>

			<div class="btn-group pull-left coms-dropdowns">
				<button class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown"><?php echo $clauses->get('order_options'); ?> <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
					<li><?php echo '<a href="' . $linksDir . 'comments/' . $postType . '/' . $postId . '/' . $actualPage . '/' . (int) $languageCheck . '/0">' . $clauses->get('coms_order_op1') . '</a></li>'; ?>
					<li><?php echo '<a href="' . $linksDir . 'comments/' . $postType . '/' . $postId . '/' . $actualPage . '/' . (int) $languageCheck . '/1">' . $clauses->get('coms_order_op2') . '</a></li>'; ?>
					<li><?php echo '<a href="' . $linksDir . 'comments/' . $postType . '/' . $postId . '/' . $actualPage . '/' . (int) $languageCheck . '/2">' . $clauses->get('coms_order_op3') . '</a></li>'; ?>
				</ul>
			</div>
		</div>
	</div>

	<div class="row<?php if (!$rootCommentsNbr) echo ' extra-margin'; ?>">
		<div class="comment-create col-lg-offset-3 col-lg-6">
<?php
			if (!\Basics\Site::parameter('anonymous_coms') AND !$currentMemberId)
				echo '<p>' . $clauses->get('guest_coms_disabled') . '</p>';
			else {
?>
				<form class="form-horizontal" method="post" action="<?php echo $linksDir . 'comments/' . $postType . '/' . $postId . '/' . $actualPage . '/' . (int) $languageCheck . '/' . (int) $order; ?>">
					<fieldset>
						<legend><?php echo $clauses->get('send_comment_title'); ?></legend>

						<div class="form-group">
							<label class="col-md-2 control-label" for="content"><?php echo $clauses->get('content'); ?></label>
							<div class="col-md-10">
								<textarea class="form-control" id="content" name="content" placeholder="<?php echo $clauses->get('bbcode_placeholder'); ?>" required></textarea>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-offset-2 col-md-10">
								<button id="send" name="send" class="btn btn-primary"><?php echo $clauses->get('send'); ?></button>
								<button id="cancel-reply" name="cancel-reply" class="btn btn-inverse"><?php echo $clauses->get('cancel'); ?></button>
							</div>
						</div>

						<input type="hidden" name="parent_id" id="parent_id" value="0">
						<input type="hidden" name="location" id="location" value="<?php echo $location; ?>">
					</fieldset>
				</form>
<?php
			}
?>
		</div>
	</div>

<?php
	if ($rootCommentsNbr) {
?>
		<hr>
		<div class="row">
			<div class="comments-list col-lg-12">
<?php
				foreach ($comments as $commentLoop)
					Basics\Templates::comment($commentLoop, $languageCheck, $hidden, true);
?>
			</div>
		</div>
<?php
	}
?>
</div>
