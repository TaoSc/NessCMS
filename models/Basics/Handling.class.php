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

		static function countEntrys($table = 'posts', $conditions = '0 = 0') {
			global $db;

			$request = $db->query('SELECT COUNT(*) total FROM ' . $table . ' WHERE ' . $conditions);

			return (int) $request->fetch(\PDO::FETCH_ASSOC)['total'];
		}

		static function idFromSlug($slug, $from = 'posts', $column =  'slug') {
			global $db;

			$request = $db->prepare('SELECT id FROM ' . $from . ' WHERE ' . $column . ' = ?');
			$request->execute([$slug]);
			$datas = $request->fetch(\PDO::FETCH_ASSOC);
			$id = $datas['id'];
			$request->closeCursor();

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