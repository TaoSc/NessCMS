<?php
	namespace Members;

	class Types {
		static function getTypes($condition = 'TRUE') {
			global $db;

			$request = $db->query('SELECT id FROM members_types WHERE ' . $condition . ' ORDER BY id');
			$typesIds = $request->fetchAll(\PDO::FETCH_ASSOC);

			$types = [];
			foreach ($typesIds as $typeLoop)
				$types[] = (new Type($typeLoop['id']))->getType();
			return $types;
		}
	}