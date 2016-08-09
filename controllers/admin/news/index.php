<?php
	$condition = '(authors_ids LIKE \'[' . $currentMemberId . ',%\' OR ' .
				 'authors_ids LIKE \'%,' . $currentMemberId . ']\' OR ' .
				 'authors_ids LIKE \'%,' . $currentMemberId . ',%\' OR ' .
				 'authors_ids = \'[' . $currentMemberId . ']\')';
	$news = \News\Handling::getNews($rights['news_publish'] ? 'TRUE' : $condition, false, false);

	$btnsGroupMenu = [['link' => $linksDir . 'admin/news/0', 'name' => $clauses->get('create_news')]];

	$pageTitle = $clauses->get('news');
	$viewPath = 'news/index';
