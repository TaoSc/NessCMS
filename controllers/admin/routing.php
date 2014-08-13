<?php
	if ($rights['admin_access'] AND !isset($params[2]) AND $params[1] === 'index' AND $foldersDepth === 1)
		include $siteDir . 'controllers/admin/index.php';
	elseif ($rights['config_edit'] AND !isset($params[2]) AND $params[1] === 'configuration' AND $foldersDepth === 1)
		include $siteDir . 'controllers/admin/configuration.php';

	else {
		error();
		$admin = false;
	}