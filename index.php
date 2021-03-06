<?php
	// Basic configuration
	mb_internal_encoding('UTF-8');
	session_start();
	$siteDir = str_replace('\\', '/', dirname(__FILE__)) . '/';
	$configFile = $siteDir . 'config.inc.php';
	$ajaxCheck = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

	// Classes auto-loading
	spl_autoload_register(function ($class) {
		global $siteDir;

		if (file_exists($siteDir . 'models/' . $class . '.class.php'))
			require $siteDir . 'models/' . $class . '.class.php';
		elseif (file_exists($siteDir . 'models/' . $class . '.php'))
			require $siteDir . 'models/' . $class . '.php';
	});

	// System installation check
	if (file_exists($configFile))
		require $configFile;
	else {
		include $siteDir . 'install.php';
		die();
	}

	// Database connection
	$isDbFilled = Basics\Site::getDB($dbHost, $dbName, $dbUser, $dbPass)->prepare('SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1;');
	$isDbFilled->execute([$dbName, 'site']);
	if (empty($isDbFilled->fetch(\PDO::FETCH_ASSOC))) {
		include $siteDir . 'install.php';
		die();
	}

	// Site related variables
	date_default_timezone_set(Basics\Site::parameter('default_timezone'));
	$topDir = Basics\Site::parameter('directory');
	if ($topDir)
		$topDir = '/' . trim($topDir, '/') . '/';
	$siteName = Basics\Site::parameter('name');

	// Language management
	if (!Basics\site::cookie('lang')) {
		Basics\site::cookie('lang', Basics\Site::parameter('default_language'));
		$language = Basics\Site::parameter('default_language');
	}
	else
		$language = Basics\site::cookie('lang');

	// Connected member management
	if (empty(Basics\site::session('member_id')) AND (Basics\site::cookie('name') AND Basics\site::cookie('password') AND !Basics\site::session('member')))
		Members\Handling::login(Basics\site::cookie('name'), Basics\site::cookie('password'));
	if (empty(Basics\site::session('member_id')))
		$currentMemberId = 0;
	else
		$currentMemberId = Basics\site::session('member_id');

	// Path handling
	if (mb_substr_count($_SERVER['REQUEST_URI'], '//'))
		die('Error while decoding the URI.');
	if (isset($_GET['location']) AND !empty($_GET['location'])) {
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

	// Buffer display
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

	// Expensive inclusions
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
		if (!Basics\site::session('member'))
			Basics\site::session('member', (new Members\Single(Basics\site::session('member_id')))->getMember(false));
		$currentMember = Basics\site::session('member');
		$rights = (new Members\Type($currentMember['type']['id']))->getRights();
	}
	else
		$rights = (new Members\Type(3))->getRights();
	$CMSVersion = 'dev';

	// Error management
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

	// Routing
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

	elseif ($params[0] === 'tags' AND isset($params[1]) AND $foldersDepth === 1)
		include $siteDir . 'controllers/tags/ajax.rel.php';

	else
		error();

	// Page display management and cache writing
	if (isset($viewPath)) {
		$cachingCond = (\Basics\Site::parameter('cache_enabled') AND (!isset($caching) OR (isset($caching) AND $caching === true)) AND !$admin);
		if ($cachingCond) {
			$memberCheck = !isset($memberCheck) ? true : $memberCheck;
			ob_start();
		}

		$viewPath = $siteDir . $theme['dir'] . 'views/' . $viewPath . '.php';
		include $siteDir . 'controllers/template.rel.php';

		if ($cachingCond)
			$cache->write($location, ob_get_flush(), $memberCheck);
	}
