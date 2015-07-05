<?php
	$news = (new News\Single($params[2], false))->getNews();

	if (empty($news) OR !Posts\Single::setViews($news['id'], true))
		error();
	else
		header('Location: ' . $linksDir . 'admin/news/' . $params[2]);