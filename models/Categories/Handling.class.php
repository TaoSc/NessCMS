<?php
	namespace Categories;

	class Handling {
		static function getCategories($condition = '0 = 0') {
			global $db;

			$request = $db->query('SELECT id FROM categories WHERE ' . $condition . ' ORDER BY id');
			$categoriesIds = $request->fetchAll(\PDO::FETCH_ASSOC);

			$categories = [];
			foreach ($categoriesIds as $categoryLoop)
				$categories[] = (new Single($categoryLoop['id']))->getCategory();
			return $categories;
		}
	}