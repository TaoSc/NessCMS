<?php
	namespace Tags;

	class Handling {
		static function getTags($condition = 'TRUE') {
			return \Basics\Handling::getList($condition, 'tags', 'Tags', 'Tag');

			// foreach ($datas as $key => $tagLoop)
				// $datas[$key]['posts_nbr'] = \Basics\Handling::countEntries('tags_relation', 'post_type = \'news\' AND tag_id = ' . $tagLoop['id']);

			// return $datas;
		}

		static function posts($tagId, $offset = 0, $limit = 9999, $postsIds = null) {
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
			$request->execute([$tagId, 'news']);
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

		static function tagAndGame($getGame = true, $id, $tagType = 'game') {
			global $db;

			if ($getGame) {
				$request = $db->prepare('
					SELECT r.post_id id
					FROM tags t, tags_relation r
					WHERE t.type = ? AND r.tag_id = t.id AND r.post_type = ? AND r.tag_id = ?
				');
				$request->execute([$tagType, 'games', $id]);
				$datas = $request->fetchAll(\PDO::FETCH_ASSOC);
			}
			else {
				$request = $db->prepare('
					SELECT t.id id
					FROM tags t, tags_relation r
					WHERE t.type = ? AND r.tag_id = t.id AND r.post_type = ? AND r.post_id = ?
				');
				$request->execute([$tagType, 'games', $id]);
				$datas = $request->fetchAll(\PDO::FETCH_ASSOC);
			}

			return $datas;
		}
	}