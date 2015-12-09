<div class="row">
<?php
	if ($headlinesPosts) {
?>
	<div class="col-md-8">
		<div id="headlines-carousel" class="carousel slide">
			<ol class="carousel-indicators">
<?php
				for ($i = 0; $i < $headlinesPostsNbr; $i++) {
					echo '<li data-target="#headlines-carousel" data-slide-to="' . $i . '"';
					if ($i === 0)
						echo ' class="active"';
					echo '></li>';
				}
?>
			</ol>

			<div class="carousel-inner">
<?php
				foreach ($headlinesPosts as $key => $postLoop) {
?>
					<div class="item<?php if ($key === 0) echo ' active'; ?>">
						<a href="<?php echo $linksDir . $postLoop['type'] . '/' . $postLoop['slug']; ?>">
							<!--data-original-->
							<img src="<?php echo \Basics\Templates::getImg('heroes/' . $postLoop['img']['slug'], $postLoop['img']['format'], 750, 400) . '" alt="' . $clauses->get('headlines_img') . ' (' . ($key + 1); ?>)">
							<div class="carousel-caption">
								<h2><?php echo $postLoop['title']; ?></h2>
								<h3><?php echo $postLoop['sub_title']; ?></h3>
							</div>
						</a>
					</div>
<?php
				}
?>
			</div>

<?php
			if ($headlinesPostsNbr > 1) {
?>
				<a class="left carousel-control" href="#headlines-carousel" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left"></span>
				</a>
				<a class="right carousel-control" href="#headlines-carousel" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right"></span>
				</a>
<?php
			}
?>
		</div>
	</div>

	<div class="col-md-4">
<?php
	}
	else
		echo '<div class="col-lg-12">';
?>
		<h1><?php echo $clauses->get('hey_folks'); ?></h1>
		<?php echo stripslashes(eval('return "' . addslashes($clauses->getDB('pages', 1, 'index_text')) . '";')); ?>
	</div>
</div>

<div class="row">
	<?php Basics\Templates::postsList($news); ?>

	<div class="col-md-4">
		<h2><a href="<?php echo $linksDir; ?>polls/" title="<?php echo $clauses->get('show_more'); ?>"><?php echo $clauses->get('poll'); ?> »</a></h2>
		<div class="well poll-sidebar">
<?php
			if ($poll) {
?>
				<blockquote>
					<?php echo $poll['question']; ?>
					<small class="pull-right poll-participants"><?php echo Basics\Strings::plural($clauses->get('participants'), $poll['total_votes']); ?></small>
				</blockquote>

				<?php Basics\Templates::pollAnswers($poll); ?>

				<a href="<?php echo $linksDir . 'polls/' . $poll['id']; ?>"><?php echo $clauses->get('more'); ?> »</a>
<?php
			}
			else
				echo $clauses->get('no_polls');
?>
		</div>

<?php
		if ($posts) {
?>
			<h2><?php echo $clauses->get('featured_content'); ?></h2>
			<ul class="nav nav-tabs top-content">
				<li class="active"><a href="#views" data-toggle="tab"><?php echo $clauses->get('most_read'); ?></a></li>
				<li><a href="#comments" data-toggle="tab"><?php echo $clauses->get('most_comment'); ?></a></li>
				<li><a href="#date" data-toggle="tab"><?php echo $clauses->get('latest_featured'); ?></a></li>
			</ul>
			<div class="tab-content well top-content">
				<div class="tab-pane fade active in" id="views">
					<?php Basics\Templates::textList($featuredPosts); ?>
				</div>
				<div class="tab-pane fade" id="comments">
					<?php Basics\Templates::textList($mostCommentedPosts); ?>
				</div>
				<div class="tab-pane fade" id="date">
					<?php Basics\Templates::textList($mostRecentPosts); ?>
				</div>
			</div>
<?php
		}
?>
	</div>
</div>