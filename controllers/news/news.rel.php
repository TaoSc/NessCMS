<?php
	$langSlug = Basics\Handling::idFromSlug($params[1], 'posts', 'slug', $language);
	$anySlug = Basics\Handling::idFromSlug($params[1], 'posts', 'slug', null);
	$news = (new News\Single($anySlug))->getNews();

	if (empty($news))
		error();
	elseif ($langSlug !== $anySlug)
		header('Location: ' . $subDir . 'news/' . $news['slug']);
	else {
		$previousNews = \News\Handling::getNews('id <' . $news['id'], true, true, '0, 1');
		if (!$previousNews) {
			$previousNews['slug'] = 'index';
			$previousNews['title'] = $clauses->get('previous');
		}
		else
			$previousNews = $previousNews[0];

		$nextNews = \News\Handling::getNews('id >' . $news['id'], true, true, '0, 1', true);
		if (!$nextNews) {
			$nextNews['slug'] = 'index';
			$nextNews['title'] = $clauses->get('next');
		}
		else
			$nextNews = $nextNews[0];

		if (($currentMemberId AND \Basics\Handling::recursiveArraySearch($currentMemberId, $news['authors']) === false) OR !$currentMemberId)
			Posts\Single::setViews($news['id']);

		$pageTitle = $news['title'] . ' - ' . $clauses->get('news');
		$viewPath = 'news/news.rel';
		$breadcrumb = [
			['name' => 'news', 'link' => 'news/'],
			['name' => $news['title']]
		];
	}