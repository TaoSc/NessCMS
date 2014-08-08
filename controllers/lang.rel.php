<?php
	if (Basics\Handling::recursiveArraySearch($params[1], Basics\Languages::getLanguages('0 = 0', false, true)) !== false) {
		setcookie('tao_lang', $params[1], time() + 63072000, $topDir, null, false, true);
		header('Location: ' . $linksDir . urldecode(str_replace(['=dot', '='], ['.', '%'], $params[2])));
	}
	else
		error();