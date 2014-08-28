<?php
	namespace Polls;

	class Handling {
		static function getPolls($condition = '0 = 0') {
			return \Basics\Handling::getList($condition, 'polls', 'Polls', 'Poll');
		}
	}