<?php
	if ($rights['admin_access'] AND !isset($params[2]) AND $params[1] === 'index' AND $foldersDepth === 1)
		include $siteDir . 'controllers/admin/index.php';

	else {
		error();
		$admin = false;
	}