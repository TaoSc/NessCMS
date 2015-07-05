<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			<h1><?php echo $news['title'] . ' <small>' . $news['sub_title'] . '</small>'; ?></h1>
		</div>

		<div class="row">
			<div class="col-lg-8">
				<?php if ($news['priority'] === 'important') echo '<span class="sprites hotThumbLow" style="left: initial;"></span>'; ?>
				<img data-original="<?php echo \Basics\Templates::getImg('heroes/' . $news['img']['slug'], $news['img']['format'], 750, 100); ?>" class="img-responsive" alt="<?php echo $clauses->get('img_thumb'); ?>">

				<hr>

				<?php echo $news['content']; ?>

				<hr>

<?php
				echo $news['category']['name'];
?>
			</div>
			<div class="col-lg-4">
<?php
				foreach ($news['authors'] as $memberLoop)
					Basics\Templates::smallUserBox($memberLoop, 'subtle-margin');
?>
				<hr>
<?php
				if ($rights['news_edit']) {
?>
					<hr>
					<div class="btn-group btn-group-justified btn-group-sm">
<?php
						echo '<a href="' . $linksDir . 'admin/news/' . $news['id'] . '" type="button" class="btn btn-warning">' . $clauses->get('modify') . '</a>';
						if ($rights['news_create'])
							echo '<a href="' . $linksDir . 'admin/news/' . $news['id'] . '/delete" type="button" class="btn btn-warning">' . $clauses->get('delete') . '</a>';
?>
					</div>
<?php
				}
?>
			</div>
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