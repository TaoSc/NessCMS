<?php
	// On récupère le dernier sondage
	$poll = (new Polls\Single(Basics\Handling::latestId('polls')))->getPoll();

	$pageTitle = $clauses->get('home');
	$viewPath = 'index';
	$breadcrumb = [['name' => 'home']];