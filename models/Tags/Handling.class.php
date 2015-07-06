<?php
	namespace Tags;

	class Handling {
		static function getTags($condition = 'TRUE') {
			return \Basics\Handling::getList($condition, 'tags', 'Tags', 'Tag');

			// foreach ($datas as $key => $tagLoop)
				// $datas[$key]['posts_nbr'] = \Basics\Handling::countEntries('tags_relation', 'post_type = \'news\' AND tag_id = ' . $tagLoop['id']);

			// return $datas;
		}

		static function tagAndGame($getGame = true, $id, $tagType = 'game') {
			global $db;

			if ($getGame) {
				$request = $db->prepare('
					SELECT r.post_id id
					FROM tags t, tags_relation r
					WHERE t.type = ? AND r.tag_id = t.id AND r.incoming_type = ? AND r.tag_id = ?
				');
				$request->execute([$tagType, 'games', $id]);
				$datas = $request->fetchAll(\PDO::FETCH_ASSOC);
			}
			else {
				$request = $db->prepare('
					SELECT t.id id
					FROM tags t, tags_relation r
					WHERE t.type = ? AND r.tag_id = t.id AND r.incoming_type = ? AND r.incoming_id = ?
				');
				$request->execute([$tagType, 'games', $id]);
				$datas = $request->fetchAll(\PDO::FETCH_ASSOC);
			}

			return $datas;
		}
	}