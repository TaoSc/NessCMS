<?php
	$tag = new Tags\Single($params[2]);

	if (empty($tag->getTag()) OR !$tag->deleteTag())
		error();
	else
		header('Location: ' . $linksDir . 'admin/tags/');