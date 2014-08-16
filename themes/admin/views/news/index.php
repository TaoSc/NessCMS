<div class="panel panel-default">
	<div class="panel-heading"><?php echo $clauses->get('news'); ?></div>

	<table class="table table-striped">
		<thead>
			<tr>
				<th><?php echo $clauses->get('title'); ?></th>
				<th><?php echo $clauses->get('date'); ?></th>
			</tr>
		</thead>
		<tbody>
<?php
			foreach ($news as $key => $newsLoop) {
?>
				<tr>
					<td><a href="<?php echo $newsLoop['id']; ?>"><?php echo $newsLoop['title']; ?></a></td>
					<td><?php Basics\Templates::dateTime($newsLoop['date'], $newsLoop['time']); ?></td>
				</tr>
<?php
			}
?>
		</tbody>
	</table>
</div>