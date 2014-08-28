<?php
	if ($params[2] === '0' AND $rights['news_create'] AND $_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($newsId = \News\Single::create($_POST['category_id'], $_POST['title'], $_POST['sub_title'], $_POST['content'], $_POST['img'], null, null, isset($_POST['visible']) ? true : 0, isset($_POST['comments']) ? true : 0))
			header('Location: ' . $linksDir . 'admin/news/' . $newsId);
		else
			error($clauses->get('news_create_fails'));
	}
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		echo 'Soon!';
		// $news = (new News\Single($params[2], false))->getNews();

		// if ($news->setNews())
			// header('Location: ' . $linksDir . 'admin/news/' . $news['id']);
		// else
			// error('news_edit_fails');
	}
	else {
		if ($params[2] === '0')
			$create = true;
		else {
			$create = false;
			$news = (new News\Single($params[2], false))->getNews();
			if ($news['visible'])
				$btnsGroupMenu[] = ['link' => $linksDir . 'news/' . $news['slug'], 'name' => $clauses->get('show_more')];
			$btnsGroupMenu[] = ['link' => $linksDir . 'admin/news/' . $news['id'] . '/delete', 'name' => $clauses->get('delete'), 'type' => 'warning'];
		}
		$categories = \Categories\Handling::getCategories();

		if (empty($news) AND !$create)
			error();
		else {
			$pageTitle = ($create ? $clauses->get('create') : $news['title']) . ' - ' . $clauses->get('news');
			$viewPath = 'news/edit.rel';
		}
	}