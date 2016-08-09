<?php
	namespace News;

	class Handling {
		public static function getNews($condition = 'TRUE', $visible = true, $languageCheck = true, $offsetLimit = false, $ascending = false) {
			return \Basics\Handling::getList('type = \'news\' AND (' . $condition . ')', 'posts', 'News', 'News', $offsetLimit, false, $ascending, null, $visible, $languageCheck);
		}
	}