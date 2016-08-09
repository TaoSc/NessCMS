<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			<h1 class="news-title"><?php echo $news['title'] . '<br><small>' . $news['sub_title'] . '</small>'; ?></h1>
		</div>

		<div class="row">
			<div class="col-lg-8">
				<?php if ($news['priority'] === 'important') echo '<span class="sprites hotThumbLow" style="left: initial;"></span>'; ?>
				<img data-original="<?php echo \Basics\Templates::getImg('heroes/' . $news['img']['slug'], $news['img']['format'], 750, 100); ?>" class="img-responsive" alt="<?php echo $clauses->get('img_thumb'); ?>">

				<hr>

				<?php echo $news['content']; ?>
			</div>

			<div class="col-lg-4">
			<!--<div data-spy="affix" data-offset-top="192" data-offset-bottom="20" style="width: 360px;top: 71px;">-->
				<div class="row">
					<div class="col-xs-12">
<?php
					foreach ($news['authors'] as $memberLoop)
						Basics\Templates::smallUserBox($memberLoop, 'subtle-margin');
?>
					</div>
				</div>

				<hr>

				<div class="row">
					<div class="col-xs-12">
						<?php Basics\Templates::dateTime($news['date'], $news['time']); ?>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<a href="<?php echo $linksDir . 'tags/' . $news['category']['slug']; ?>" class="label label-primary"><span class="glyphicon glyphicon-folder-open small"></span> <?php echo $news['category']['name']; ?></a>
<?php
						foreach ($news['tags'] as $tagLoop) {
?>
							<a href="<?php echo $linksDir . 'tags/' . $tagLoop['slug']; ?>" class="label label-primary"><span class="glyphicon glyphicon-tag small"></span> <?php echo $tagLoop['name']; ?></a>
<?php
						}
?>
					</div>
				</div>

<?php
				if ($news['votes']) {
?>
					<hr>

					<div class="row">
						<div class="col-xs-6">
							<button type="button" class="btn icon-btn rounded-btn btn-success btn-block vote-btn"<?php if ($voteBtnsCond) echo ' disabled'; ?> data-id="<?php echo $news['id']; ?>" data-type="posts" value="up">
								<span class="glyphicon glyphicon-thumbs-up img-circle text-success"></span> <?php echo $clauses->get('to_like'); ?> 
								(<span class="votes-nbr"><?php echo $news['likes']; ?></span>)								
							</button>
						</div>
						<div class="col-xs-6">
							<button type="button" class="btn icon-btn rounded-btn btn-danger btn-block vote-btn"<?php if ($voteBtnsCond) echo ' disabled'; ?> data-id="<?php echo $news['id']; ?>" data-type="posts" value="down">
								<span class="glyphicon glyphicon-thumbs-down img-circle text-danger"></span> <?php echo $clauses->get('to_dislike'); ?> 
								(<span class="votes-nbr"><?php echo $news['dislikes']; ?></span>)								
							</button>
						</div>
					</div>
<?php
				}

				if ($news['edit_cond'] OR $news['removal_cond']) {
?>
					<hr>

					<div class="row">
						<div class="col-xs-12 btn-group btn-group-justified btn-group-sm">
<?php
							if ($news['edit_cond'])
								echo '<a href="' . $linksDir . 'admin/news/' . $news['id'] . '" type="button" class="btn btn-warning">' . $clauses->get('modify') . '</a>';
							if ($news['removal_cond'])
								echo '<a href="' . $linksDir . 'admin/news/' . $news['id'] . '/delete" type="button" class="btn btn-warning">' . $clauses->get('delete') . '</a>';
?>
						</div>
					</div>
<?php
				}
?>
			</div>
			<!--</div>-->
		</div>

		<div class="bottom-link">
			<ul class="pager">
<?php
				echo '<li class="previous';
				if (!isset($previousNews['id'])) echo ' disabled';
				echo '"><a href="' . $linksDir . 'news/' . $previousNews['slug'] . '"';
				echo '>&larr; ' . $previousNews['title'] . '</a></li>';

				echo '<li class="next';
				if (!isset($nextNews['id'])) echo ' disabled';
				echo '"><a href="' . $linksDir . 'news/' . $nextNews['slug'] . '"';
				echo '>' . $nextNews['title'] . ' &rarr;</a></li>';
?>
			</ul>
		</div>
	</div>
</div>

<?php
	if ($news['comments']) {
?>
		<div class="row">
			<div class="col-lg-12 subtle-line-top">
				<?php Comments\Handling::view($news['id'], 'posts'); ?>
			</div>
		</div>
<?php
	}
