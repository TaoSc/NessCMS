<?php
	namespace Polls;

	class Handling {
		public static function getPolls($condition = 'TRUE') {
			return \Basics\Handling::getList($condition, 'polls', 'Polls', 'Poll');
		}
	}
