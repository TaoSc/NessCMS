<?php
	namespace Basics;

	class Handling {
		static function getList($condition = '0 = 0', $type = 'comments', $typePlural = 'Comments', $typeSingle = 'Comment', $offsetLimit = false, $idsOnly = false, $ascending = false, ...$extras) {
			global $db, $language;
			$order = $ascending ? 'ASC' : 'DESC';
			if ($offsetLimit)
				$offsetLimit = ' LIMIT ' . $offsetLimit;

			$request = $db->query('SELECT id FROM ' . $type . ' WHERE ' . $condition . ' ORDER BY id ' . $order . $offsetLimit);
			$ids = $request->fetchAll(\PDO::FETCH_ASSOC);

			if ($idsOnly)
				return $ids;
			else {
				$className = '\\' . $typePlural . '\Single';
				$array = [];
				foreach ($ids as $element) 
					$array[] = (new $className($element['id']))->{'get' . $typeSingle}(...$extras);
				return array_filter($array);
			}
		}

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

		static function idFromSlug($slug, $tableName = 'posts', $column =  'slug', $noLanguage = true) {
			global $db;

			if ($noLanguage !== true) {
				global $clauses;
				return $clauses->getDB($tableName, $slug, $column, $noLanguage, true, true);
			}
			else {
				$request = $db->prepare('SELECT id FROM ' . $tableName . ' WHERE ' . $column . ' = ?');
				$request->execute([$slug]);
				$datas = $request->fetch(\PDO::FETCH_ASSOC);
				return $datas['id'];
			}
		}

		static function latestId($from = 'posts', $select = 'id') {
			global $db;

			$request = $db->query('SELECT ' . $select . ' FROM ' . $from . ' ORDER BY id DESC LIMIT 1');
			$datas = $request->fetch(\PDO::FETCH_ASSOC);
			return $datas[$select];
		}
	}