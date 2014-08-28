<?php
	$news = new News\Single($params[2], false);

	if (empty($news->getNews()) OR !$news->deleteNews())
		error();
	else
		header('Location: ' . $linksDir . 'admin/news/');