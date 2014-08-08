<?php
	if ($ajaxCheck)
		include $siteDir. 'views/members/login.ajax.php';
	else
		header('Location: ' . $linksDir . 'members/login');