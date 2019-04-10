<?php
	$newsObject = new News\Single(Basics\Handling::idFromSlug($params[1], 'posts', 'slug', false));
	$news = $newsObject->getNews();

	if (empty($news))
		error();
	elseif ($news['slug'] !== $params[1])
		header('Location: ' . $linksDir . 'news/' . $news['slug']);
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

		$hasVoted = \Votes\Handling::did($news['id'], 'posts');
		$voteBtnsCond = ($hasVoted OR (!$currentMemberId AND !Basics\Site::parameter('anonymous_votes')) OR !$news['visible']);

		if (($currentMemberId AND \Basics\Handling::recursiveArraySearch($currentMemberId, $news['authors']) === false) OR !$currentMemberId)
			$newsObject->setViews();

		$pageTitle = $news['title'] . ' - ' . $clauses->get('news');
		$viewPath = 'news/news.rel';
		$breadcrumb = [
			['name' => 'news', 'link' => 'news/'],
			['name' => $news['title']]
		];
	}
