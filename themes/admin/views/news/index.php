<div class="panel panel-default">
	<div class="panel-heading"><?php echo $clauses->get('news'); ?></div>

	<table class="table table-striped">
		<thead>
			<tr>
				<th><?php echo $clauses->get('title'); ?></th>
				<th><?php echo $clauses->get('date'); ?></th>
				<th><?php echo $clauses->get('views'); ?></th>
			</tr>
		</thead>
		<tbody>
<?php
			foreach ($news as $key => $newsLoop) {
?>
				<tr>
					<td><a href="<?php echo $linksDir . 'admin/news/' . $newsLoop['id']; ?>"><?php echo $newsLoop['title']; ?></a></td>
					<td><?php Basics\Templates::dateTime($newsLoop['date'], $newsLoop['time']); ?></td>
					<td><?php
						if ($newsLoop['views'] > 0)
							echo '<a href="' . $linksDir . 'admin/news/' . $newsLoop['id'] . '/reset-views" title="' .
							$clauses->get('reset_views') . '">' .$newsLoop['views'] . '</a>';
						else
							echo $newsLoop['views'];
					?></td>
				</tr>
<?php
			}
?>
		</tbody>
	</table>
</div>