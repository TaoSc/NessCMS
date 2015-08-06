<?php
	namespace Posts;

	class Handling {
		static function getPosts($condition = 'TRUE', $visible = true, $languageCheck = true, $offsetLimit = false, $ascending = false) {
			global $db, $language;
			$order = $ascending ? 'ASC' : 'DESC';
			if ($offsetLimit)
				$offsetLimit = ' LIMIT ' . $offsetLimit;

			$request = $db->query('SELECT id, type FROM posts WHERE ' . $condition . ' ORDER BY id ' . $order . $offsetLimit);
			$posts = $request->fetchAll(\PDO::FETCH_ASSOC);

			$array = [];
			foreach ($posts as $element) {
				$postType = ucfirst($element['type']);
				$className = '\\' . $postType . '\\Single';
				$array[] = call_user_func([(new $className($element['id'], $visible, $languageCheck)), 'get' . $postType]);
			}

			return array_values(array_filter($array));
		}
	}