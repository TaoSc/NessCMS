<?php
	namespace Members;

	class Types {
		static function getTypes($condition = 'TRUE') {
			return \Basics\Handling::getList($condition, 'members_types', 'Members', 'Type', false, false, true);
		}
	}