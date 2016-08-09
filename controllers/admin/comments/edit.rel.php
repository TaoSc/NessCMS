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

		if (!$comment['hidden']) {
			// Not great at all, it's not aware of the pagging system
			if ($comment['post_type'] === 'polls') {
				$post = (new Polls\Single($comment['post_id']))->getPoll();
				$postLink = 'polls/' . $post['id'];
			}
			elseif ($comment['post_type'] === 'posts') {
				$post = Posts\Handling::getPosts('id = ' . $comment['post_id'])[0];
				$postLink = $post['type'] . '/' . $post['slug'];
			}

			if (isset($post) AND $post)
				$btnsGroupMenu[] = ['link' => $linksDir . $postLink . '#comment-' . $comment['id'], 'name' => $clauses->get('show_more')];
		}

		$pageTitle = Basics\Strings::cropTxt($comment['content'], 10) . ' - ' . $clauses->get('comments');
		$viewPath = 'comments/edit.rel';
	}
