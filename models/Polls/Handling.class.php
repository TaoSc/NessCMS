<?php
	namespace Polls;

	class Handling {
		static function getPolls($condition = '0 = 0') {
			global $db;

			$request = $db->query('SELECT id FROM polls WHERE ' . $condition . ' ORDER BY id DESC');
			$pollsIds = $request->fetchAll(\PDO::FETCH_ASSOC);

			$polls = [];
			foreach ($pollsIds as $pollLoop)
				$polls[] = (new Single($pollLoop['id']))->getPoll();
			return $polls;
		}
	}