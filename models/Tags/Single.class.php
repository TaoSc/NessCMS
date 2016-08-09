<?php
	namespace Tags;

	class Single {
		protected $tag;
		public static $types = [
			'tag',
			'game',
			'developer',
			'publisher',
			'system',
			'genre',
			'category'
		];

		public function __construct($id, $type = null) {
			$request = \Basics\Site::getDB()->prepare('SELECT id, author_id, type FROM tags WHERE id = ?' . ($type === null ? null : ' AND type = \'' . $type . '\''));
			$request->execute([$id]);
			$this->tag = $request->fetch(\PDO::FETCH_ASSOC);
		}

		public function getTag() {
			if ($this->tag) {
				global $clauses;

				$this->tag['slug'] = $clauses->getDB('tags', $this->tag['id'], 'slug');
				$this->tag['name'] = $clauses->getDB('tags', $this->tag['id'], 'name');

				$this->tag['author'] = (new \Members\Single($this->tag['author_id']))->getMember(false);
			}

			return $this->tag;
		}

		public function getPosts($offset = 0, $limit = 9999, $postsIds = null, $visible = true) {
			$postsIds = (array) $postsIds;

			$condition = null;
			foreach ($postsIds as $element)
				$condition .= ' AND p.id != ' . $element;

			$request = \Basics\Site::getDB()->prepare('
				SELECT p.id
				FROM posts p
				INNER JOIN tags_relation r
				ON p.id = r.incoming_id
				WHERE r.tag_id = ? ' . ($condition ?? null) . ' AND r.incoming_type = ?
				ORDER BY p.id DESC LIMIT ' . $offset . ', ' . $limit
			);
			$request->bindParam(':offset', $offset, \PDO::PARAM_INT);
			$request->bindParam(':limit', $limit, \PDO::PARAM_INT);
			$request->execute([$this->tag['id'], 'news']);
			$datas = $request->fetchAll(\PDO::FETCH_ASSOC);

			if (!$datas)
				return [];

			foreach ($datas as $element)
				$condition .= ' OR id = ' . $element['id'];
			$condition = trim($condition, ' OR ');

			return \Posts\Handling::getPosts($condition, $visible, true, $offset . ', ' . $limit);
		}

		public function setTag($name, $type) {
			$slug = \Basics\Strings::slug($name);
			$slugBeing = \Basics\Handling::idFromSlug($slug, 'tags', 'slug', false);

			if (!empty($slug) AND $type AND (!$slugBeing OR $slugBeing === $this->tag['id']) AND $this->tag) {
				global $clauses;

				$clauses->setDB('tags', $this->tag['id'], true, ['name', $name], ['slug', $slug]);

				$request = \Basics\Site::getDB()->prepare('UPDATE tags SET type = ? WHERE id = ?');
				$request->execute([$type, $this->tag['id']]);

				return true;
			}
			else
				return false;
		}

		public function deleteTag() {
			if ($this->tag AND $this->tag['id'] != 1) {
				$db = \Basics\Site::getDB();

				$request = $db->prepare('DELETE FROM tags WHERE id = ?');
				$request->execute([$this->tag['id']]);

				$request = $db->prepare('DELETE FROM languages_routing WHERE table_name = ? AND incoming_id = ?');
				$request->execute(['tags', $this->tag['id']]);

				$request = $db->prepare('DELETE FROM tags_relation WHERE tag_id = ?');
				$request->execute([$this->tag['id']]);

				return true;
			}
			else
				return false;
		}

		public static function create($name, $type) {
			if (!empty($name) AND !empty($type)) {
				$slug = \Basics\Strings::slug($name);

				if (\Basics\Handling::idFromSlug($slug, 'tags', 'slug', false))
					return false;
				else {
					global $currentMemberId, $clauses;

					$request = \Basics\Site::getDB()->prepare('INSERT INTO tags(author_id, type) VALUES(?, ?)');
					$request->execute([$currentMemberId, $type]);

					$tagId = \Basics\Handling::latestId('tags');

					$clauses->setDB('tags', $tagId, false, ['name', $name], ['slug', $slug]);

					return $tagId;
				}
			}
			else
				return false;
		}
	}
