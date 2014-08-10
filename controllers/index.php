<?php
	$poll = (new Polls\Single(Basics\Handling::latestId('polls')))->getPoll();

	$news = \News\Handling::getNews('visible = true', true, '0, 5');

	foreach ($news as $key => $newsLoop) {
		if ($newsLoop['priority'] === 'important')
			$width = 750;
		elseif ($newsLoop['priority'] === 'normal')
			$width = 250;

		$news[$key]['img_address'] = \Basics\Templates::getImg('heroes/' . $newsLoop['img']['slug'], $newsLoop['img']['format'], $width, 100);
	}

	$pageTitle = $clauses->get('home');
	$viewPath = 'index';
	$breadcrumb = [['name' => 'home']];