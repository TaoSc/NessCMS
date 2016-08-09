<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST' AND !empty($_POST['index_text'])) {
		$clauses->setDB('pages', 1, true, ['index_text', $_POST['index_text']]);

		header('Refresh: 0');
	}
	else {
		$indexText = $clauses->getDB('pages', 1, 'index_text');

		$pageTitle = $clauses->get('home');
		$viewPath = 'index';
	}
