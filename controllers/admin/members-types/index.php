<?php
	$types = \Members\Types::getTypes();

	$btnsGroupMenu = [['link' => $linksDir . 'admin/members-types/0', 'name' => $clauses->get('create_members_type')]];

	$pageTitle = $clauses->get('members_types');
	$viewPath = 'members-types/index';
