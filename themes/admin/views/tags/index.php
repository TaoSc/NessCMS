<div class="panel panel-default">
	<div class="panel-heading"><?php echo $clauses->get('tags'); ?></div>

	<table class="table table-striped">
		<thead>
			<tr>
				<th><?php echo $clauses->get('name'); ?></th>
				<th><?php echo $clauses->get('type'); ?></th>
			</tr>
		</thead>
		<tbody>
<?php
			foreach ($tags as $key => $tagLoop) {
?>
				<tr>
					<td><a href="<?php echo $tagLoop['id']; ?>"><?php echo $tagLoop['name']; ?></a></td>
					<td><?php echo $clauses->get($tagLoop['type']); ?></td>
				</tr>
<?php
			}
?>
		</tbody>
	</table>
</div>