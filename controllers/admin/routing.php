<?php
	if ($rights['admin_access'] AND isset($params[1]) AND $params[1] === 'index' AND $foldersDepth === 1)
		include $siteDir . 'controllers/admin/index.php';
	elseif ($rights['config_edit'] AND isset($params[1]) AND $params[1] === 'configuration' AND $foldersDepth === 1)
		include $siteDir . 'controllers/admin/configuration.php';

	elseif ($rights['admin_access'] AND isset($params[2]) AND $params[1] === 'members-types' AND $params[2] === 'index' AND $foldersDepth === 2)
		include $siteDir . 'controllers/admin/members-types/index.php';
	elseif ($rights['admin_access'] AND isset($params[2]) AND $params[1] === 'members-types' AND is_numeric($params[2]) AND $foldersDepth === 2)
		include $siteDir . 'controllers/admin/members-types/edit.rel.php';
	elseif ($rights['admin_access'] AND isset($params[3]) AND $params[1] === 'members-types' AND is_numeric($params[2]) AND $params[3] === 'delete' AND $foldersDepth === 3)
		include $siteDir . 'controllers/admin/members-types/delete.rel.php';

	elseif (($rights['news_create'] OR $rights['news_edit']) AND isset($params[2]) AND $params[1] === 'news' AND $params[2] === 'index' AND $foldersDepth === 2)
		include $siteDir . 'controllers/admin/news/index.php';
	elseif (($rights['news_create'] OR $rights['news_edit']) AND isset($params[2]) AND $params[1] === 'news' AND is_numeric($params[2]) AND $foldersDepth === 2)
		include $siteDir . 'controllers/admin/news/edit.rel.php';
	elseif (($rights['news_create']) AND isset($params[3]) AND $params[1] === 'news' AND is_numeric($params[2]) AND $params[3] === 'delete' AND $foldersDepth === 3)
		include $siteDir . 'controllers/admin/news/delete.rel.php';
	elseif ($rights['news_edit'] AND isset($params[3]) AND $params[1] === 'news' AND is_numeric($params[2]) AND $params[3] === 'reset-views' AND $foldersDepth === 3)
		include $siteDir . 'controllers/admin/news/reset_views.rel.php';

	elseif ($rights['admin_access'] AND isset($params[2]) AND $params[1] === 'tags' AND $params[2] === 'index' AND $foldersDepth === 2)
		include $siteDir . 'controllers/admin/tags/index.php';
	elseif ($rights['admin_access'] AND isset($params[2]) AND $params[1] === 'tags' AND is_numeric($params[2]) AND $foldersDepth === 2)
		include $siteDir . 'controllers/admin/tags/edit.rel.php';
	elseif ($rights['admin_access'] AND isset($params[3]) AND $params[1] === 'tags' AND is_numeric($params[2]) AND $params[3] === 'delete' AND $foldersDepth === 3)
		include $siteDir . 'controllers/admin/tags/delete.rel.php';

	elseif ($currentMemberId AND isset($params[2]) AND $params[1] === 'comments' AND is_numeric($params[2]) AND $foldersDepth === 2)
		include $siteDir . 'controllers/admin/comments/edit.rel.php';
	elseif ($currentMemberId AND isset($params[3]) AND $params[1] === 'comments' AND is_numeric($params[2]) AND $params[3] === 'delete' AND $foldersDepth === 3)
		include $siteDir . 'controllers/admin/comments/delete.rel.php';

	else {
		error();
		$admin = false;
	}