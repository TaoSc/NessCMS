<?php
	namespace Basics;

	class Handling {
		static function ipAddress() {
			$ip = $_SERVER['REMOTE_ADDR'];

			if (!empty($_SERVER['HTTP_CLIENT_IP']))
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

			return $ip;
		}

		static function recursiveArraySearch($needle, $haystack) {
			foreach ($haystack as $key => $value) {
				$currentKey = $key;
				if ($needle === $value OR (is_array($value) && Handling::recursiveArraySearch($needle, $value) !== false))
					return $currentKey;
			}
			return false;
		}

		static function twoDimSorting($array, $keyName) {
			$keysArray = [];
			foreach ($array as $key => $element)
				$keysArray[$key] = $element[$keyName];
			arsort($keysArray);

			$tempArray = [];
			foreach ($keysArray as $key => $element)
				$tempArray[] = $array[$key];

			return $tempArray;
		}

		static function countEntrys($table = 'posts', $conditions = '0 = 0') {
			global $db;

			$request = $db->query('SELECT COUNT(*) total FROM ' . $table . ' WHERE ' . $conditions);

			return (int) $request->fetch(\PDO::FETCH_ASSOC)['total'];
		}

		static function idFromSlug($slug, $tableName = 'posts', $column =  'slug', $language = false) {
			global $db;

			if ($language) {
				$request = $db->prepare('SELECT incoming_id FROM languages_routing WHERE table_name = ? AND language = ? AND column_name = ? AND value = ?');
				$request->execute([$tableName, $language, $column, $slug]);
				$datas = $request->fetch(\PDO::FETCH_ASSOC);
				$id = $datas['incoming_id'];
				$request->closeCursor();
			}
			else {
				$request = $db->prepare('SELECT id FROM ' . $tableName . ' WHERE ' . $column . ' = ?');
				$request->execute([$slug]);
				$datas = $request->fetch(\PDO::FETCH_ASSOC);
				$id = $datas['id'];
				$request->closeCursor();
			}

			return $id;
		}

		static function latestId($from = 'posts', $select = 'id') {
			global $db;

			$request = $db->query('SELECT ' . $select . ' FROM ' . $from . ' ORDER BY id DESC LIMIT 1');
			$datas = $request->fetch(\PDO::FETCH_ASSOC);
			$id = $datas[$select];
			$request->closeCursor();

			return $id;
		}
	}