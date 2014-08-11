<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			<h1><?php echo $news['title'] . ' <small>' . $news['sub_title'] . '</small>'; ?></h1>
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

<div class="row">
	<div class="col-lg-12 subtle-line-top">
		<?php Comments\Handling::view($news['id'], 'posts'); ?>
	</div>
</div>