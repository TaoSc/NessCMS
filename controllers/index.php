<?php
	$news = \News\Handling::getNews('TRUE', true, true, '0, 5');
	foreach ($news as $key => $newsLoop) {
		$height = 100;
		if ($newsLoop['priority'] === 'important')
			$width = 750;
		elseif ($newsLoop['priority'] === 'normal')
			$width = 250;
		elseif ($newsLoop['priority'] === 'low') {
			$width = 200;
			$height = 70;
		}

		$news[$key]['img_address'] = \Basics\Templates::getImg('heroes/' . $newsLoop['img']['slug'], $newsLoop['img']['format'], $width, $height);
	}

	$poll = (new Polls\Single(Basics\Handling::latestId('polls')))->getPoll();

	$headlinesPosts = Posts\Handling::getPosts('priority = \'important\'');
	$headlinesPostsNbr = count($headlinesPosts);
	$posts = Posts\Handling::getPosts('TRUE', true, true, 10);

	$featuredPosts = \Basics\Handling::twoDimSorting($posts, 'views');
	$tempFeaturedPosts = [];
	foreach ($featuredPosts as $postLoop)
		$tempFeaturedPosts[] = ['label' => $postLoop['views'] . ' <span class="glyphicon glyphicon-eye-open"></span>',
								'text' => $postLoop['title'],
								'link' => $linksDir . $postLoop['type'] . '/' . $postLoop['slug'] . '" title="' . $postLoop['sub_title']];
	$featuredPosts = &$tempFeaturedPosts;

	$mostCommentedPosts = \Basics\Handling::twoDimSorting($posts, 'comments_nbr');
	$tempMostCommentedPosts = [];
	foreach ($mostCommentedPosts as $postLoop)
		$tempMostCommentedPosts[] = ['label' => $postLoop['comments_nbr'] . ' <span class="glyphicon glyphicon-comment"></span>',
									 'text' => $postLoop['title'],
									 'link' => $linksDir . $postLoop['type'] . '/' . $postLoop['slug'] . '" title="' . $postLoop['sub_title']];
	$mostCommentedPosts = &$tempMostCommentedPosts;

	$mostRecentPosts = [];
	foreach ($posts as $postLoop)
		$mostRecentPosts[] = ['label' => Basics\Dates::sexyDate($postLoop['date'], true, true) . ' ' . $clauses->get('at') . ' ' . $postLoop['time'],
							  'text' => $postLoop['title'],
							  'link' => $linksDir . $postLoop['type'] . '/' . $postLoop['slug'] . '" title="' . $postLoop['sub_title']];

	$pageTitle = $clauses->get('home');
	$viewPath = 'index';
	$breadcrumb = [['name' => 'home']];