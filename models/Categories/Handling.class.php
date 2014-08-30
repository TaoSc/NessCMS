<?php
	namespace Categories;

	class Handling {
		static function getCategories($condition = 'TRUE') {
			$condition = 'type = \'category\' AND (' . $condition . ')';

			return \Basics\Handling::getList($condition, 'tags', 'Categories', 'Category');
		}
	}