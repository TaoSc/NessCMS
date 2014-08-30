<?php
	$news = \News\Handling::getNews('TRUE', false, false);

	$btnsGroupMenu = [['link' => $linksDir . 'admin/news/0', 'name' => $clauses->get('create_news')]];

	$pageTitle = $clauses->get('news');
	$viewPath = 'news/index';