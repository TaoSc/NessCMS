<?php
	$comment = new Comments\Single($params[2], false);

	if (empty($comment->getComment(false, false)) OR !$comment->deleteComment($rights['admin_access']))
		error();
	else
		header('Location: ' . $_SERVER['HTTP_REFERER']);
