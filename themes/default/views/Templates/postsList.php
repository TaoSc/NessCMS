<div class="col-md-8 news-list">
<?php
	if ($params[0] === 'index' AND $foldersDepth === 0)
		echo '<h2>' . $clauses->get('latest_news') . '</h2>';

	foreach ($postsArray as $postLoop) {
?>
		<div class="col-lg-12 <?php echo $postLoop['priority']; ?> news<?php if ($postLoop['priority'] !== 'important') echo ' no-padding'; ?>">
			<a href="<?php echo $linksDir . $postLoop['type'] . '/' . $postLoop['slug']; ?>">
				<?php if ($postLoop['priority'] === 'important') echo '<span class="sprites hotThumbLow"></span>'; ?>
				<img data-original="<?php echo $postLoop['img_address']; ?>" alt="<?php echo $clauses->get('img_thumb'); ?>">
				<?php if ($postLoop['priority'] === 'important') echo '<div class="mask"></div>'; ?>
				<h3><?php echo $postLoop['title']; ?></h3>
				<h4><?php echo $postLoop['sub_title']; ?> 
					<small>
						— <?php Basics\Templates::dateTime($postLoop['date'], $postLoop['time']); ?>
<?php
						if ($postLoop['comments_nbr']) {
?>
							 <span class="badge"><?php echo $postLoop['comments_nbr']; ?> <span class="glyphicon glyphicon-comment"></span></span>
<?php
						}
?>
					</small>
				</h4>
			</a>
		</div>
<?php
	}
	if (!$postsArray)
		echo $clauses->get($emptyMessage);
?>
</div>
