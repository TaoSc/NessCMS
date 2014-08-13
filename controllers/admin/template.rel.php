<?php
	$actualLang = $clauses->getLanguage();
	$languagesList = Basics\Languages::getLanguages('code != \'' . $language . '\' AND enabled = true', $actualLang['code']);

	$navigation = [
		['caption' => $clauses->get('home'), 'link' => 'admin/index'],
		['caption' => $clauses->get('config'), 'link' => 'admin/configuration'],
	];

	include $siteDir . $theme['dir'] . 'views/template.rel.php';