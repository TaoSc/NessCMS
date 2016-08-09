<?php
	if (Basics\Handling::recursiveArraySearch($params[1], Basics\Languages::getLanguages('TRUE', false, true)) !== false) {
		Basics\site::cookie('lang', $params[1]);
		header('Location: ' . $linksDir . urldecode(str_replace(['=dot', '='], ['.', '%'], $params[2])));
	}
	else
		error();
