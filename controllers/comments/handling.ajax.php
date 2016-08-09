<?php
	$supportedTypes = ['polls', 'posts'];

	if ($ajaxCheck AND is_numeric($params[3] . $params[4] . $params[5]) AND in_array($params[1], $supportedTypes))
		Comments\Handling::view($params[2], $params[1], $params[3], $params[4], $params[5]);
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['location']) AND Comments\Single::create($_POST['parent_id'], $params[2], $params[1], $_POST['content']))
			header('Location: ' . $linksDir . $_POST['location']);
		else
			error($clauses->get('comment_fail'));
	}
	else
		error();
