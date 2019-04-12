<?php
	namespace Basics;

	class Site {
		protected static $db;

		public static function getDB($dbHost = null, $dbName = null, $dbUser = null, $dbPass = null) {
			if (!isset(self::$db)) {
				try {
					self::$db = new \PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8', $dbUser, $dbPass);

					self::$db->query('SET NAMES \'utf8mb4\'');
					self::$db->query('SET CHARACTER SET utf8mb4');
					self::$db->query('SET COLLATION_CONNECTION = \'utf8mb4_general_ci\'');
				} catch (Exception $error) {
					die('Error with <b>PHP Data Objects</b>: <pre>' . $error->getMessage() . '</pre>');
				}
			}

			return self::$db;
		}

		public static function parameter($name, $newValue = null) {
			$db = self::getDB();

			if ($newValue === null) {
				$request = $db->prepare('SELECT value FROM site WHERE name = ?');
				$request->execute([$name]);
				$value = $request->fetch(\PDO::FETCH_ASSOC);

				return empty($value) ? false : $value['value'];
			}
			else {
				$request = $db->prepare('UPDATE site SET value = ? WHERE name = ?');
				$request->execute([$newValue, $name]);
			}
		}

		public static function cookie($name, $newValue = null, $extraTime = 63072000) {
			global $siteName, $topDir;

			if ($newValue === null)
				return !isset($_COOKIE[Strings::slug($siteName) . '_' . $name]) ? false : $_COOKIE[Strings::slug($siteName) . '_' . $name];
			else
				setcookie(Strings::slug($siteName) . '_' . $name, $newValue, time() + $extraTime, $topDir, null, false, true);
		}

		public static function session($name, $newValue = null) {
			global $siteName;

			if ($newValue === null)
				return !isset($_SESSION[Strings::slug($siteName) . '_' . $name]) ? false : $_SESSION[Strings::slug($siteName) . '_' . $name];
			else
				$_SESSION[Strings::slug($siteName) . '_' . $name] = $newValue;
		}
	}
