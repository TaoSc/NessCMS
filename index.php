<?php
	// Quelques configurations basiques
	$siteDir = dirname(__FILE__) . '/';
	$configFile = $siteDir . 'config.inc.php';
	$ajaxCheck = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	session_start();
	mb_internal_encoding('UTF-8');

	// Auto-chargement des classes
	spl_autoload_register(function ($class) {
		global $siteDir;

		if (file_exists($siteDir . 'models/' . $class . '.class.php'))
			require $siteDir . 'models/' . $class . '.class.php';
		elseif (file_exists($siteDir . 'models/' . $class . '.php'))
			require $siteDir . 'models/' . $class . '.php';
	});

	// Vérifications du système
	if (file_exists($configFile))
		include $configFile;
	else {
		include $siteDir . 'install.php';
		die();
	}

	// Connexion à la base de données
	try {
		$db = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8', $dbUser, $dbPass);
	}
	catch (Exception $error) {
		die('Error with <b>PHP Data Objects</b> : ' . $error->getMessage());
	}

	// Variables liées au site
	$topDir = Basics\Site::parameter('directory');
	$siteName = Basics\Site::parameter('name');

	// Gestion de la langue
	if (!isset($_COOKIE['nesscms_lang'])) {
		setcookie('nesscms_lang', Basics\Site::parameter('default_language'), time() + 63072000, $topDir, null, false, true);
		$language = Basics\Site::parameter('default_language');
	}
	else
		$language = $_COOKIE['nesscms_lang'];

	// Gestion du membre
	if (isset($_COOKIE['nesscms_name']) AND isset($_COOKIE['nesscms_password']) AND !isset($_SESSION['member']))
		Members\Handling::login($_COOKIE['nesscms_name'], $_COOKIE['nesscms_password']);
	if (empty($_SESSION['id']))
		$currentMemberId = 0;
	else
		$currentMemberId = &$_SESSION['id'];

	// Gestion du chemin entré en paramètre
	if (mb_substr_count($_SERVER['REQUEST_URI'], '//'))
		die('Error while decoding the URI.');
	if (isset($_GET['location'])) {
		$location = $_GET['location'];
		if (mb_substr($location, -1) === '/')
			$location .= 'index';
	}
	else
		$location = 'index';

	$foldersDepth = mb_substr_count($location, '/');
	if ($ajaxCheck AND isset($_SERVER['HTTP_REFERER']))
		$relativeFoldersDepth = mb_substr_count($_SERVER['HTTP_REFERER'], '/') - (mb_substr_count($topDir, '/') + 2);
	else
		$relativeFoldersDepth = &$foldersDepth;

	$params = explode('/', $location);
	if ($params[$foldersDepth] === '')
		unset($params[$foldersDepth]);

	if (Basics\Site::parameter('url_rewriting')) {
		if (!empty($_SERVER['QUERY_STRING']) AND mb_substr_count($_SERVER['REQUEST_URI'], $_SERVER['QUERY_STRING']))
			header('Location: ' . $topDir);

		for ($i = 0, $subDir = null; $i < $relativeFoldersDepth; $i++)
			$subDir .= '../';
		if (empty($subDir))
			$subDir = './';
		$linksDir = &$subDir;
	}
	else {
		$subDir = './';
		$linksDir = $subDir . 'index.php?location=';
	}

	// Affichage du cache
	$cache = new Basics\Cache($siteDir . 'cache', 10);
	if ($_SERVER['REQUEST_METHOD'] === 'GET' AND !$ajaxCheck) {
		if ($cache->exist($location))
			$memberCheck = true;
		elseif ($cache->exist($location, false))
			$memberCheck = false;

		if (isset($memberCheck)) {
			if (headers_sent())
				$encoding = false;
			elseif (mb_strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)
				$encoding = 'x-gzip';
			elseif (mb_strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)
				$encoding = 'gzip';
			else
				$encoding = false;

			if ($encoding) {
				$content = $cache->get($location, $memberCheck, false);
				header('Content-Encoding: ' . $encoding);
				header('Content-Length: ' . strlen($content));
				die($content);
			}
		}
	}

	// Appels coûteux en terme de performance
	if ($params[0] === 'admin' AND $foldersDepth !== 0) {
		include $siteDir . 'themes/admin/theme.php';
		$admin = true;
	}
	else {
		include $siteDir . 'themes/' . \Basics\Site::parameter('theme') . '/theme.php';
		$admin = false;
	}
	$theme['dir'] = 'themes/' . $theme['dir'];
	$clauses = new Basics\Languages($language);
	if ($currentMemberId) {
		if (!isset($_SESSION['member']))
			$_SESSION['member'] = (new Members\Single($_SESSION['id']))->getMember(false);
		$currentMember = &$_SESSION['member'];
		$rights = (new Members\Type($currentMember['type']['id']))->getRights();
	}
	else
		$rights = (new Members\Type(3))->getRights();
	$siteVersion = 'dev';

	// Gestion des erreurs
	function error($errorMsg = 404, $showHomeBtn = true) {
		global $siteDir, $clauses, $theme, $language, $admin, $siteName, $location, $linksDir, $subDir, $currentMemberId, $rights, $currentMember;

		if ($errorMsg === 404)
			header('HTTP/1.0 404 Not Found');
		elseif ($errorMsg === 403)
			header('HTTP/1.0 403 Forbidden');

		if (is_int($errorMsg))
			$errorMsg = $clauses->get('error') . ' ' . $errorMsg . '.';

		include $siteDir . 'themes/' . \Basics\Site::parameter('theme') . '/theme.php';
		$admin = false;
		$theme['dir'] = 'themes/' . $theme['dir'];

		$pageTitle = $clauses->get('error');
		$viewPath = $siteDir . $theme['dir'] . 'views/error.rel.php';
		include $siteDir . 'controllers/template.rel.php';
		die();
	}

	// Routage
	$controllerPath = $siteDir . 'controllers/' . $location . '.php';

	if ($admin)
		include $siteDir . 'controllers/admin/routing.php';
	elseif (file_exists($controllerPath))
		include $controllerPath;
	elseif ($params[0] === 'lang' AND isset($params[2]) AND $foldersDepth === 2)
		include $siteDir . 'controllers/lang.rel.php';

	elseif ($params[0] === 'news' AND isset($params[1]) AND $foldersDepth === 1)
		include $siteDir . 'controllers/news/news.rel.php';

	elseif ($params[0] === 'polls' AND isset($params[1]) AND is_numeric($params[1]) AND $foldersDepth === 1)
		include $siteDir . 'controllers/polls/poll.rel.php';
	elseif ($params[0] === 'polls' AND isset($params[2]) AND $params[2] === 'send' AND $foldersDepth === 2)
		include $siteDir . 'controllers/polls/send.ajax.php';

	elseif ($params[0] === 'comments' AND isset($params[5]) AND $foldersDepth === 5)
		include $siteDir . 'controllers/comments/handling.ajax.php';

	elseif ($params[0] === 'members' AND isset($params[3]) AND $params[1] === 'login' AND $params[2] === 'ajax' AND $foldersDepth === 3)
		include $siteDir . 'controllers/members/login.ajax.php';
	elseif ($params[0] === 'members' AND isset($params[2]) AND ($params[1] === 'login' OR $params[1] === 'logout') AND $foldersDepth === 2)
		include $siteDir . 'controllers/members/' . $params[1] . '.php';
	elseif ($params[0] === 'members' AND isset($params[1]) AND $foldersDepth === 2)
		include $siteDir . 'controllers/members/profile.rel.php';

	elseif ($params[0] === 'votes' AND isset($params[2]) AND $foldersDepth === 2)
		include $siteDir . 'controllers/votes/ajax.rel.php';

	else
		error();

	// Gestion de l'affichage de la page et de l'écriture du cache
	if (isset($viewPath)) {
		$cachingCond = !$admin AND (!isset($caching) OR (isset($caching) AND $caching === true));
		if ($cachingCond) {
			$memberCheck = !isset($memberCheck) ? true : $memberCheck;
			ob_start();
		}

		$viewPath = $siteDir . $theme['dir'] . 'views/' . $viewPath . '.php';
		include $siteDir . 'controllers/template.rel.php';

		// if ($cachingCond)
			// $cache->write($location, ob_get_flush(), $memberCheck);
	}