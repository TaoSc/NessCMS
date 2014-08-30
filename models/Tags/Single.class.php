<?php
	namespace Tags;

	class Single {
		private $tag;

		function __construct($id, $type = null) {
			global $db;

			$request = $db->prepare('SELECT id, author_id, type FROM tags WHERE id = ?' . ($type === null ? null : ' AND type = \'' . $type . '\''));
			$request->execute([$id]);
			$this->tag = $request->fetch(\PDO::FETCH_ASSOC);
		}

		function getTag() {
			if ($this->tag) {
				global $clauses;

				$this->tag['slug'] = $clauses->getDB('tags', $this->tag['id'], 'slug');
				$this->tag['name'] = $clauses->getDB('tags', $this->tag['id'], 'name');

				$this->tag['author'] = (new \Members\Single($this->tag['author_id']))->getMember(false);
			}

			return $this->tag;
		}

		function getPosts($offset = 0, $limit = 9999, $postsIds = null) {
			global $db;
			$postsIds = (array) $postsIds;

			foreach ($postsIds as $element) {
				if (isset($condition))
					$condition .= ' AND p.id != ' . $element;
				else
					$condition = 'AND p.id != ' . $element;
			}

			$request = $db->prepare('
				SELECT p.id
				FROM posts p
				INNER JOIN tags_relation r
				ON p.id = r.post_id
				WHERE r.tag_id = ? ' . (isset($condition) ? $condition : null) . ' AND r.post_type = ?
				ORDER BY p.id DESC LIMIT ' . $offset . ', ' . $limit
			);
			$request->bindParam(':offset', $offset, \PDO::PARAM_INT);
			$request->bindParam(':limit', $limit, \PDO::PARAM_INT);
			$request->execute([$this->tag['id'], 'news']);
			$datas = $request->fetchAll(\PDO::FETCH_ASSOC);

			if (!$datas)
				return [];

			foreach ($datas as $key => $element) {
				if ($key)
					$condition .= ' OR p.id = ' . $element['id'];
				else
					$condition = 'p.id = ' . $element['id'];
			}

			return (new \Posts\Handling($offset, $limit, false, $condition))->getPosts();
		}

		/*function setTag($newName, $newType) {
			$newSlug = \Basics\Strings::slug($newName)
			if (!empty($newName) AND !empty($newSlug) AND $newType AND $this->tag) {
				if (\Basics\Handling::countEntries('tags', 'slug = \'' .  . '\' AND id != ' . $this->tag['id']))
					return false;
				else {
					global $db;

					$request = $db->prepare('UPDATE tags SET name = ?, slug = ?, type = ? WHERE id = ?');
					$request->execute([$newName, $newSlug, $newType, $this->tag['id']]);

					return true;
				}
			}
			else
				return false;
		}*/

		function deleteTag() {
			if ($this->tag) {
				global $db;

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

		static function create($name, $type) {
			if (!empty($name) AND !empty($type)) {
				$slug = \Basics\Strings::slug($name);

				if (\Basics\Handling::idFromSlug($slug, 'tags', 'slug', false))
					return false;
				else {
					global $db, $currentMemberId, $language;

					$request = $db->prepare('INSERT INTO tags(author_id, type) VALUES(?, ?)');
					$request->execute([$currentMemberId, $type]);

					$tagId = \Basics\Handling::latestId('tags');

					$request = $db->prepare('
						INSERT INTO languages_routing (id, language, incoming_id, table_name, column_name, value)
						VALUES (?, ?, ?, \'tags\', \'name\', ?),
						(?, ?, ?, \'tags\', \'slug\', ?)
					');
					$request->execute([
						\Basics\Strings::identifier(),
						$language,
						$tagId,
						$name,

						\Basics\Strings::identifier(),
						$language,
						$tagId,
						$slug
					]);

					return $tagId;
				}
			}
			else
				return false;
		}
	}