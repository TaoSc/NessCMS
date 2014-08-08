<?php
	$supportedTypes = ['polls', 'news', 'reviews'];

	if ($ajaxCheck AND is_numeric($params[3] . $params[4] . $params[5]) AND array_search($params[1], $supportedTypes) !== false)
		Comments\Handling::view($params[2], $params[1], $params[3], $params[4], $params[5]);
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['location']) AND isset($_POST['parent_id']) AND isset($_POST['content']) AND Comments\Single::createComment($_POST['parent_id'], $params[2], $params[1], $_POST['content']))
			header('Location: ' . $linksDir . $_POST['location']);
		else
			error($clauses->get('comment_fail'));
	}
	else
		error();