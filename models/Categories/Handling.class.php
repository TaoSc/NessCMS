<?php
	namespace Categories;

	class Handling {
		public static function getCategories($condition = 'TRUE') {
			return \Basics\Handling::getList('type = \'category\' AND (' . $condition . ')', 'tags', 'Categories', 'Category');
		}
	}
