<?php
	namespace Categories;

	class Handling {
		static function getCategories($condition = '0 = 0') {
			$condition = 'type = \'category\' AND (' . $condition . ')';

			return \Basics\Handling::getList($condition, 'tags', 'Categories', 'Category');
		}
	}