<?php
	$news = (new News\Single(Basics\Handling::idFromSlug($params[1], 'posts', 'slug', $language)))->getNews();

	if (empty($news))
		error();
	else {
		$pageTitle = $news['title'] . ' - ' . $clauses->get('news');
		$viewPath = 'news/news.rel';
		$breadcrumb = [
			['name' => 'news', 'link' => 'news/'],
			['name' => $news['title']]
		];
	}