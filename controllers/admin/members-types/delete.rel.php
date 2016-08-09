<?php
	$type = new Members\Type($params[2]);

	if (empty($type->getType()) OR !$type->deleteType())
		error($clauses->get('unauthorized_removal'));
	else
		header('Location: ' . $linksDir . 'admin/members-types/');
