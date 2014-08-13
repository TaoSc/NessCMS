<?php
	namespace Basics;

	class Site {
		static function parameter($name, $newValue = null) {
			global $db;

			if ($newValue === null) {
				$request = $db->prepare('SELECT value FROM site WHERE name = ?');
				$request->execute([$name]);
				$value = $request->fetch(\PDO::FETCH_ASSOC);

				if (empty($value))
					return false;
				else
					return $value['value'];
			}
			else {
				$request = $db->prepare('UPDATE site SET value = ? WHERE name = ?');
				$request->execute([$newValue, $name]);
			}
		}
	}