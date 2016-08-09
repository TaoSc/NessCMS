<?php
	$tags = \Tags\Handling::getTags();

	$btnsGroupMenu = [['link' => $linksDir . 'admin/tags/0', 'name' => $clauses->get('create_tag')]];

	$pageTitle = $clauses->get('tags');
	$viewPath = 'tags/index';
