<?php
	$news = (new News\Single(Basics\Handling::idFromSlug($params[1], 'posts', 'slug', $language)))->getNews();

	if (empty($news))
		error();
	else {
		$previousNews = \News\Handling::getNews('id <' . $news['id'], true, true, '0, 1');
		if (!$previousNews) {
			$previousNews['slug'] = 'index';
			$previousNews['title'] = $clauses->get('previous');
		}
		else
			$previousNews = $previousNews[0];

		$nextNews = \News\Handling::getNews('id >' . $news['id'], true, true, '0, 1', 'ASC');
		if (!$nextNews) {
			$nextNews['slug'] = 'index';
			$nextNews['title'] = $clauses->get('next');
		}
		else
			$nextNews = $nextNews[0];

		// if (($currentMemberId AND \Basics\Handling::recursiveArraySearch($currentMemberId, $news['authors']) === false) OR !$currentMemberId)
			// Posts\Handling::setViews($news['id']);

		$pageTitle = $news['title'] . ' - ' . $clauses->get('news');
		$viewPath = 'news/news.rel';
		$breadcrumb = [
			['name' => 'news', 'link' => 'news/'],
			['name' => $news['title']]
		];
	}