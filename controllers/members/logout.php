<?php
	if ($currentMemberId) {
		Members\Handling::logout();

		if (isset($params[2]) AND !mb_strpos($params[2], '=2Fadmin=2F'))
			header('Location: ' . $linksDir . urldecode(str_replace(['=dot', '='], ['.', '%'], $params[2])));
		else
			header('Location: ' . $linksDir);
	}
	else
		error($clauses->get('login_needed'));
