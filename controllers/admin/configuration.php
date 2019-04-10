<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (is_numeric($_POST['coms_per_page']))
			Basics\Site::parameter('coms_per_page', $_POST['coms_per_page']);

		Basics\Site::parameter('anonymous_coms', isset($_POST['anonymous_coms']) ? true : 0);

		Basics\Site::parameter('anonymous_votes', isset($_POST['anonymous_votes']) ? true : 0);

		Basics\Site::parameter('private_emails', isset($_POST['private_emails']) ? true : 0);

		if (Basics\Site::parameter('cache_enabled') AND !isset($_POST['cache_enabled']))
			$cache->clear();
		Basics\Site::parameter('cache_enabled', isset($_POST['cache_enabled']) ? true : 0);

		Basics\Site::parameter('url_rewriting', isset($_POST['url_rewriting']) ? true : 0);

		if (isset($_POST['default_language']))
			Basics\Site::parameter('default_language', $_POST['default_language']);

		if (isset($_POST['default_user_type']))
			Basics\Site::parameter('default_user_type', $_POST['default_user_type']);

		if (isset($_POST['name']) AND $_POST['name'] !== $siteName) {
			$cookiesEnabled = Basics\site::cookie('name') AND Basics\site::cookie('password');

			Basics\Site::cookie('lang', 'garbage', -1);
			Basics\Site::cookie('name', 'garbage', -1);
			Basics\Site::cookie('password', 'garbage', -1);

			Basics\Site::parameter('name', $_POST['name']);
			$siteName = Basics\Site::parameter('name');

			Basics\site::cookie('lang', $language);

			if ($cookiesEnabled) {
				Basics\Site::cookie('name', $currentMember['nickname']);
				Basics\Site::cookie('password', $currentMember['password']);
			}
		}

		header('Location: ' . $topDir . (Basics\Site::parameter('url_rewriting') ? '' : 'index.php?location=') . $location);
	}

	$languages = \Basics\Languages::getLanguages();
	$membersTypes = \Members\Types::getTypes();

	$pageTitle = $clauses->get('config');
	$viewPath = 'configuration';
