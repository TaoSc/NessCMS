<?php
	if ($params[2] === '0' AND $rights['news_create'] AND $_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($newsId = \News\Single::create($_POST['category_id'], $_POST['title'], $_POST['sub_title'], $_POST['content'], $_POST['img'], null, null, isset($_POST['visible']) ? true : 0, isset($_POST['comments']) ? true : 0))
			header('Location: ' . $linksDir . 'admin/news/' . $newsId);
		else
			error($clauses->get('news_create_fails'));
	}
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$news = new News\Single($params[2], false);

		if ($news->setNews($_POST['title'], $_POST['sub_title'], $_POST['content'], $_POST['category_id'], $_POST['img'], isset($_POST['visible']) ? true : 0, isset($_POST['availability']) ? true : 0, isset($_POST['comments']) ? true : 0))
			header('Refresh: 0');
		else
			error($clauses->get('news_edit_fails'));
	}
	else {
		if ($params[2] === '0')
			$create = true;
		else {
			$create = false;
			$news = (new News\Single($params[2], false))->getNews();
			if ($news['visible'])
				$btnsGroupMenu[] = ['link' => $linksDir . 'news/' . $news['slug'], 'name' => $clauses->get('show_more')];
			if ($news['views'])
				$btnsGroupMenu[] = ['link' => $linksDir . 'admin/news/' . $news['id'] . '/reset-views', 'name' => $clauses->get('reset_views'), 'type' => 'warning'];
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