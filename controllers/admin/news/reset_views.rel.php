<?php
	$news = (new News\Single($params[2], false))->getNews();

	if (empty($news) OR !Posts\Single::setViews($news['id'], true))
		error();
	else
		header('Location: ' . $_SERVER['HTTP_REFERER']);