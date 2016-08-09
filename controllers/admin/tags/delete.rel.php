<?php
	$tag = new Tags\Single($params[2]);
	$tagContent = $tag->getTag();
	if ($tagContent['type'] === 'category')
		$tag = new Categories\Single($params[2]);

	if (empty($tagContent) OR !$tag->deleteTag())
		error($clauses->get('unauthorized_removal'));
	else
		header('Location: ' . $linksDir . 'admin/tags/');
