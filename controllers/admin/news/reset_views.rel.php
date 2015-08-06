<?php
	$news = new News\Single($params[2], false);

	if (empty($news->getNews()) OR !$news->setViews(true))
		error();
	else
		header('Location: ' . $_SERVER['HTTP_REFERER']);