<?php
	if ($currentMemberId)
		error($clauses->get('already_logged_in'));
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (Members\Handling::registration($_POST['name'], $_POST['email'], $_POST['pwd'], $_POST['pwd2'], isset($_POST['cookies'])))
			header('Location: ' . $linksDir);
		else
			error(stripslashes(eval($clauses->getMagic('registration_fail'))), false);
	}
	else {
		$pageTitle = $clauses->get('registration');
		$viewPath = 'members/registration';
		$breadcrumb = [['name' => 'members'], ['name' => 'registration']];
	}
