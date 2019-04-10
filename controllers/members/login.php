<?php
	if ($currentMemberId)
		error($clauses->get('already_logged_in'));
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (Members\Handling::login($_POST['name'], $_POST['pwd'], isset($_POST['cookies']), true)) {
			if (isset($_POST['redirection']))
				$redirection = $_POST['redirection'];
			elseif (isset($params[2]))
				$redirection = $params[2];
			else
				$redirection = null;

			if ($redirection == 'members=2Fregistration')
				$redirection = null;

			header('Location: ' . $linksDir . urldecode(str_replace(['=dot', '='], ['.', '%'], $redirection)));
		}
		else
			error(stripslashes(eval($clauses->getMagic('login_bad_pass'))), false);
	}
	else {
		$pageTitle = $clauses->get('login');
		$viewPath = 'members/login';
		$breadcrumb = [['name' => 'members'], ['name' => 'login']];
	}
