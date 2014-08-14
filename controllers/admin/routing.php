<?php
	if ($rights['admin_access'] AND isset($params[1]) AND $params[1] === 'index' AND $foldersDepth === 1)
		include $siteDir . 'controllers/admin/index.php';
	elseif ($rights['config_edit'] AND isset($params[1]) AND $params[1] === 'configuration' AND $foldersDepth === 1)
		include $siteDir . 'controllers/admin/configuration.php';

	elseif ($rights['admin_access'] AND isset($params[2]) AND $params[1] === 'news' AND $params[2] === 'index' AND $foldersDepth === 2)
		include $siteDir . 'controllers/admin/news/index.php';

	else {
		error();
		$admin = false;
	}