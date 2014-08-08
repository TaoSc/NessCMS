<?php
	namespace Members;

	class Type {
		private $type;

		function __construct($id) {
			global $db;

			$request = $db->prepare('SELECT id, rights FROM members_types WHERE id = ?');
			$request->execute([$id]);
			$this->type = $request->fetch(\PDO::FETCH_ASSOC);
		}

		function getType() {
			if ($this->type) {
				global $clauses;

				$typeOnly = $clauses->getDB('members_types', $this->type['id']);
				$typeOnly['id'] = $this->type['id'];

				return $typeOnly;
			}
			else
				return false;
		}

		function getRights() {
			if ($this->type)
				return json_decode($this->type['rights'], true);
			else
				return false;
		}
	}