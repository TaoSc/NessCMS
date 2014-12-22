<?php
	namespace Basics;

	class Handling {
		static function getList($condition = 'TRUE', $type = 'comments', $namespaces = 'Comments', $accessor = 'Comment', $offsetLimit = false, $idsOnly = false, $ascending = false, $methodParams = null, ...$instanceParams) {
			global $db, $language;
			$order = $ascending ? 'ASC' : 'DESC';
			if ($offsetLimit)
				$offsetLimit = ' LIMIT ' . $offsetLimit;

			$request = $db->query('SELECT ' . ($type == 'languages' ? 'code id' : 'id') . ' FROM ' . $type . ' WHERE ' . $condition . ' ORDER BY id ' . $order . $offsetLimit);
			$ids = $request->fetchAll(\PDO::FETCH_ASSOC);

			if ($idsOnly)
				return $ids;
			else {
				$className = '\\' . $namespaces . '\\' . ($type == 'languages' ? 'Languages' : 'Single');
				$array = [];
				foreach ($ids as $element)
					$array[] = call_user_func_array([(new $className($element['id'], ...$instanceParams)), 'get' . $accessor], (array) $methodParams);
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

		static function countEntries($table = 'posts', $conditions = 'TRUE') {
			global $db;

			$request = $db->query('SELECT COUNT(*) total FROM ' . $table . ' WHERE ' . $conditions);

			return (int) $request->fetch(\PDO::FETCH_ASSOC)['total'];
		}

		static function idFromSlug($slug, $tableName = 'posts', $column =  'slug', $noLanguage = true) {
			if ($noLanguage !== true) {
				global $clauses;

				return $clauses->getDB($tableName, $slug, $column, true, true, $noLanguage);
			}
			else {
				global $db;

				$request = $db->prepare('SELECT id FROM ' . $tableName . ' WHERE ' . $column . ' = ?');
				$request->execute([$slug]);

				return $request->fetch(\PDO::FETCH_ASSOC)['id'];
			}
		}

		static function latestId($from = 'posts', $select = 'id') {
			global $db;

			$request = $db->query('SELECT ' . $select . ' FROM ' . $from . ' ORDER BY id DESC LIMIT 1');

			return $request->fetch(\PDO::FETCH_ASSOC)[$select];
		}
	}