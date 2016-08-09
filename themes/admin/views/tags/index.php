<div class="panel panel-default">
	<div class="panel-heading"><?php echo $clauses->get('tags'); ?></div>

<?php
	if ($tags) {
?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?php echo $clauses->get('name'); ?></th>
					<th><?php echo $clauses->get('type'); ?></th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach ($tags as $tagLoop) {
?>
					<tr>
						<td><a href="<?php echo $linksDir . 'admin/tags/' . $tagLoop['id']; ?>"><?php echo $tagLoop['name']; ?></a></td>
						<td><?php echo $clauses->get($tagLoop['type']); ?></td>
					</tr>
<?php
				}
?>
			</tbody>
		</table>
<?php
	}
	else
		echo '<div class="panel-body">' . $clauses->get('no_tags'); '</div>';
?>
</div>
