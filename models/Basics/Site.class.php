<?php
	namespace Basics;

	class Site {
		static function parameter($name, $newValue = null) {
			global $db;

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

		static function cookie($name, $newValue = null) {
			global $siteName, $topDir;

			if ($newValue === null) {
				return !isset($_COOKIE[Strings::slug($siteName) . '_' . $name]) ? false : $_COOKIE[Strings::slug($siteName) . '_' . $name];
			}
			else
				setcookie(Strings::slug($siteName) . '_' . $name, $newValue, time() + 63072000, $topDir, null, false, true);
		}

		static function session($name, $newValue = null) {
			global $siteName;
			if ($newValue === null)
				return !isset($_SESSION[Strings::slug($siteName) . '_' . $name]) ? false : $_SESSION[Strings::slug($siteName) . '_' . $name];
			else
				$_SESSION[Strings::slug($siteName) . '_' . $name] = $newValue;
		}
	}