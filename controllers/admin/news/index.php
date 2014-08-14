<?php
	$news = \News\Handling::getNews('0 = 0', false, false);

	$pageTitle = $clauses->get('news');
	$viewPath = 'news/index';