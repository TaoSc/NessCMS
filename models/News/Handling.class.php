<?php
	namespace News;

	class Handling {
		static function getNews($condition = '0 = 0', $visible = true, $languageCheck = true, $offsetLimit = false, $ascending = false) {
			$condition = 'type = \'news\' AND (' . $condition . ')';

			return \Basics\Handling::getList($condition, 'posts', 'News', 'News', $offsetLimit, false, $ascending, $visible, $languageCheck);
		}
	}