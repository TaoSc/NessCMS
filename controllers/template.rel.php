<?php
	// $backgroundImage = ' style="background-image: url(\'' . $subDir . 'images/backgrounds/' . mt_rand(2, 21) . '.jpg\');"';

	$actualLang = $clauses->getLanguage();
	$languagesList = Basics\Languages::getLanguages('code != \'' . $language . '\' AND enabled = true', $actualLang['code']);

	if ($admin) {
		$navigation = [
			['caption' => $clauses->get('home'), 'link' => 'admin/index'],
			['caption' => $clauses->get('news'), 'link' => 'admin/news/index'],
			['caption' => $clauses->get('tags'), 'link' => 'admin/tags/index'],
			['caption' => $clauses->get('config'), 'link' => 'admin/configuration'],
		];
	}
	else {
		$navigation = [
			['caption' => $clauses->get('home'), 'link' => 'index'],
			['caption' => $clauses->get('news'), 'link' => 'news/index'],
			['caption' => $clauses->get('reviews'), 'link' => 'reviews/index'],
			['caption' => $clauses->get('forum'), 'link' => 'forum/index']
		];
	}

	if (isset($breadcrumb)) {
		foreach ($breadcrumb as $key => $helperElem) {
			$name = $clauses->get($helperElem['name']);
			if (!empty($name))
				$breadcrumb[$key]['name'] = $name;
		}
	}

	include $siteDir . $theme['dir'] . 'views/template.rel.php';