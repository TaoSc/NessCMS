<?php
	if ($ajaxCheck)
		include $siteDir . $theme['dir'] . 'views/members/login.ajax.php';
	else
		header('Location: ' . $linksDir . 'members/login');
