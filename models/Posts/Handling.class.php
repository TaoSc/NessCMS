<?php
	namespace Posts;

	class Handling {
		static function getPosts($condition = 'TRUE', $visible = true, $languageCheck = true, $offsetLimit = false, $ascending = false) {
			return \Basics\Handling::getList($condition, 'posts', 'Posts', 'Post', $offsetLimit, false, $ascending, null, null, $visible, $languageCheck);
		}
	}