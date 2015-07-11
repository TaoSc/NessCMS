<?php
	if ($currentMemberId)
		$createNewsLink = 'admin/news/0';
	else
		$createNewsLink = 'members/login/admin=2Fnews=2F0';

	$pageTitle = $clauses->get('news');
	$viewPath = 'news/index';
	$breadcrumb = [['name' => 'news']];