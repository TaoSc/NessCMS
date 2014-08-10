<?php
	if ($rights['admin_access'] AND !isset($params[2]) AND $foldersDepth = 1)
		include $siteDir . 'controllers/admin/index.php';