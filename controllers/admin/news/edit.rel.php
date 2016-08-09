<?php
	if ($params[2] === '0' AND $rights['news_create'] AND $_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($newsId = \News\Single::create($_POST['title'], $_POST['sub_title'], $_POST['content'], $_POST['category_id'], $_POST['tags'],
			$_POST['img'], null, isset($_POST['visible']) ? true : 0, $_POST['priority'], isset($_POST['comments']) ? true : 0, isset($_POST['votes']) ? true : 0, $rights))
			header('Location: ' . $linksDir . 'admin/news/' . $newsId);
		else
			error($clauses->get('news_create_fails'));
	}
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$news = new News\Single($params[2], false);

		if ($news->setNews($_POST['title'], $_POST['sub_title'], $_POST['content'], $_POST['category_id'], $_POST['tags'], $_POST['img'],
						   isset($_POST['visible']) ? true : 0, isset($_POST['availability']) ? true : 0, $_POST['priority'],
						   isset($_POST['comments']) ? true : 0, isset($_POST['votes']) ? true : 0))
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

			if (!empty($news)) {
				if ($news['visible'])
					$btnsGroupMenu[] = ['link' => $linksDir . 'news/' . $news['slug'], 'name' => $clauses->get('show_more')];
				if ($news['views'])
					$btnsGroupMenu[] = ['link' => $linksDir . 'admin/news/' . $news['id'] . '/reset-views', 'name' => $clauses->get('reset_views'), 'type' => 'warning'];
				if ($news['removal_cond'])
					$btnsGroupMenu[] = ['link' => $linksDir . 'admin/news/' . $news['id'] . '/delete', 'name' => $clauses->get('delete'), 'type' => 'warning'];

				$tagsIds = $news['raw_tags'];
				$tempTagsIds = [];
				foreach ($tagsIds as $tagLoop)
					$tempTagsIds[] = (int) $tagLoop['id'];
				$tagsIds = &$tempTagsIds;
			}
		}

		$categories = \Categories\Handling::getCategories();
		$tagsTypes = Tags\Single::$types;
		$postsPriorities = News\Single::$priorities;
		array_pop($tagsTypes); // to remove the "categories" type from the list
		$firstTagsType = array_values($tagsTypes)[0];

		if (empty($news) AND !$create)
			error();
		else {
			$pageTitle = ($create ? $clauses->get('create') : $news['title']) . ' - ' . $clauses->get('news');
			$viewPath = 'news/edit.rel';
		}
	}
