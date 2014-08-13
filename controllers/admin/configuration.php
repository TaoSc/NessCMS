<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		Basics\Site::parameter('anonymous_coms', isset($_POST['anonymous_coms']) ? true : false);
		Basics\Site::parameter('anonymous_votes', isset($_POST['anonymous_votes']) ? true : false);
		Basics\Site::parameter('private_emails', isset($_POST['private_emails']) ? true : false);
		Basics\Site::parameter('url_rewriting', isset($_POST['url_rewriting']) ? true : false);

		if (isset($_POST['name'])) {
			Basics\Site::parameter('name', $_POST['name']);
			header('Location: ' . $linksDir . 'admin/configuration');
		}
	}

	$pageTitle = $clauses->get('config');
	$viewPath = 'configuration';