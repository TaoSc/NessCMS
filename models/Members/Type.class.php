<?php
	namespace Members;

	class Type {
		private $type;
		public static $rights = [
			'admin_access',
			'config_edit',
			'news_create',
			'news_publish',
			'news_edit',
			'comment_edit',
			'comment_moderate',
			'poll_create'
		];

		public function __construct($id) {
			$request = \Basics\Site::getDB()->prepare('SELECT id, rights FROM members_types WHERE id = ?');
			$request->execute([$id]);
			$this->type = $request->fetch(\PDO::FETCH_ASSOC);

			if ($this->type) {
				global $rights;

				$this->type['removal_cond'] = ($rights['admin_access'] AND ($this->type['id'] > 3 OR $rights['config_edit']));
				$this->type['edit_cond'] = ($rights['admin_access'] AND (!in_array($this->type['id'], [1, 3]) OR $rights['config_edit']));
			}
		}

		public function getType() {
			if ($this->type) {
				global $clauses;

				$typeOnly = $clauses->getDB('members_types', $this->type['id']);
				$typeOnly['id'] = $this->type['id'];
				$typeOnly['count'] = \Basics\Handling::countEntries('members', 'type_id = ' . $this->type['id']);

				return $typeOnly;
			}
			else
				return false;
		}

		public function getRights() {
			if ($this->type)
				return json_decode($this->type['rights'], true);
			else
				return false;
		}

		public function getMembers() {
			return \Basics\Handling::getList('type_id = ' . $this->type['id'], 'members', 'Members', 'Member', false, false, false, false);
		}

		public function setType($name, $rights) {
			$slug = \Basics\Strings::slug($name);
			$slugBeing = \Basics\Handling::idFromSlug($slug, 'members_types', 'slug', false);

			if ($this->type AND !empty($slug) AND (!$slugBeing OR $slugBeing === $this->type['id']) AND !empty($rights) AND $this->type['edit_cond']) {
				global $clauses;

				$clauses->setDB('members_types', $this->type['id'], true, ['name', $name], ['slug', $slug]);

				$request = \Basics\Site::getDB()->prepare('UPDATE members_types SET rights = ? WHERE id = ?');
				$request->execute([json_encode($rights), $this->type['id']]);

				return true;
			}
			else
				return false;
		}

		public function deleteType() {
			if ($this->type AND $this->type['removal_cond']) {
				$db = \Basics\Site::getDB();

				$request = $db->prepare('DELETE FROM members_types WHERE id = ?');
				$request->execute([$this->type['id']]);

				$request = $db->prepare('DELETE FROM languages_routing WHERE table_name = ? AND incoming_id = ?');
				$request->execute(['members_types', $this->type['id']]);

				$request = $db->prepare('UPDATE members SET type_id = ? WHERE type_id = ?');
				$request->execute([\Basics\Site::parameter('default_users_type'), $this->type['id']]);

				return true;
			}
			else
				return false;
		}

		public static function create($name, $rights) {
			global $rights;
			
			$slug = \Basics\Strings::slug($name);
			$slugBeing = \Basics\Handling::idFromSlug($slug, 'members_types', 'slug', false);

			if (!$slugBeing AND !empty($slug) AND !empty($rights) AND $rights['admin_access']) {
				global $clauses;

				$request = \Basics\Site::getDB()->prepare('INSERT INTO members_types (rights) VALUES (?)');
				$request->execute([json_encode($rights)]);

				$typeId = \Basics\Handling::latestId('members_types');

				$clauses->setDB('members_types', $typeId, false, ['name', $name], ['slug', $slug]);

				return $typeId;
			}
			else
				return false;
		}
	}
