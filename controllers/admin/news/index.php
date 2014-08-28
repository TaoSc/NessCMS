<?php
	$news = \News\Handling::getNews('0 = 0', false, false);

	$btnsGroupMenu = [['link' => $linksDir . 'admin/news/0', 'name' => $clauses->get('create_news')]];

	$pageTitle = $clauses->get('news');
	$viewPath = 'news/index';