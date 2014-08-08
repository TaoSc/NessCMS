<?php
	namespace Basics;

	class Site {
		static function parameter($name) {
			global $db;

			$request = $db->prepare('SELECT value FROM site WHERE name = ?');
			$request->execute([$name]);
			$value = $request->fetch(\PDO::FETCH_ASSOC);

			if (empty($value))
				return false;
			else
				return $value['value'];
		}
	}