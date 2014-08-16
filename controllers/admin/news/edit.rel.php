<?php
	if ($params[2] === '0' AND $_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($newsId = \News\Single::create())
			header('Location: ' . $subDir . 'admin/edit/' . $newsId);
		else
			error('news_create_fails');
	}
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		echo 'Soon!';
	}
	else {
		if ($params[2] === '0')
			$news['title'] = 'Creation';
		else
			$news = (new News\Single($params[2]))->getNews();

		if (empty($news))
			error();
		else {
			$pageTitle = $news['title'] . ' - ' . $clauses->get('news');
			$viewPath = 'news/edit.rel';
		}
	}