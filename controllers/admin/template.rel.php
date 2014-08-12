<?php
	$navigation = [
		['caption' => $clauses->get('home'), 'link' => 'admin/index'],
		['caption' => $clauses->get('config'), 'link' => 'admin/configuration'],
	];

	include $siteDir . 'themes/admin/views/template.rel.php';