<?php
	namespace Posts;

	class Handling {
		public static function getPosts($condition = 'TRUE', $visible = true, $languageCheck = true, $offsetLimit = false, $ascending = false) {
			$order = $ascending ? 'ASC' : 'DESC';
			if ($offsetLimit)
				$offsetLimit = ' LIMIT ' . $offsetLimit;

			$request = \Basics\Site::getDB()->query('SELECT id, type FROM posts WHERE ' . $condition . ' ORDER BY id ' . $order . $offsetLimit);
			$posts = $request->fetchAll(\PDO::FETCH_ASSOC);

			$array = [];
			foreach ($posts as $element) {
				$postType = \Basics\Strings::ucFirst($element['type']);
				$className = '\\' . $postType . '\\Single';
				$array[] = call_user_func([(new $className($element['id'], $visible, $languageCheck)), 'get' . $postType]);
			}

			return array_values(array_filter($array));
		}
	}
