<?php
	if ($currentMemberId)
		error($clauses->get('already_logged_in'));
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (Members\Handling::login($_POST['name'], hash('sha256', $_POST['pwd']), $_POST['cookies'])) {
			if ($_POST['redirection'])
				header('Location: ' . $linksDir . urldecode(str_replace(['=dot', '='], ['.', '%'], $_POST['redirection'])));
			else
				header('Location: ' . $linksDir);
		}
		else
			error(stripslashes(eval($clauses->getMagic('login_bad_pass'))), false);
	}
	else {
		$pageTitle = $clauses->get('login');
		$viewPath = 'members/login';
		$breadcrumb = [['name' => 'members'], ['name' => 'login']];
	}