<?php
	$commentObj = new Comments\Single($params[2], false);
	$comment = $commentObj->getComment(false, false);

	if (empty($comment) OR !$comment['edit_cond'])
		error();
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($commentObj->setComment($_POST['content'], $_POST['hidden']))
			header('Refresh: 0');
		else
			error($clauses->get('comment_edit_fails'));
	}
	else {
		$comment = $commentObj->getComment(false);
		$hideOptions = [
			['id' => 0, 'name' => 'visible'],
			['id' => 1, 'name' => 'hidden']
		];
		if ($rights['comment_moderate'])
			$hideOptions[] = ['id' => 2, 'name' => 'act_as_deleted'];

		// if (!$comment['hidden'])
			// $btnsGroupMenu[] = ['link' => $linksDir . 'news/' . $news['slug'], 'name' => $clauses->get('show_more')];

		$pageTitle = Basics\Strings::cropTxt($comment['content'], 10) . ' - ' . $clauses->get('comments');
		$viewPath = 'comments/edit.rel';
	}