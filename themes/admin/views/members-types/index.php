<div class="panel panel-default">
	<div class="panel-heading"><?php echo $clauses->get('members_types'); ?></div>

<?php
	if ($types) {
?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?php echo $clauses->get('name'); ?></th>
					<th><?php echo $clauses->get('members'); ?></th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach ($types as $typeLoop) {
?>
					<tr>
						<td><a href="<?php echo $linksDir . 'admin/members-types/' . $typeLoop['id']; ?>"><?php echo $typeLoop['name']; ?></a></td>
						<td><?php echo $typeLoop['count']; ?></td>
					</tr>
<?php
				}
?>
			</tbody>
		</table>
<?php
	}
	else
		echo '<div class="panel-body">' . $clauses->get('no_members_type'); '</div>';
?>
</div>
