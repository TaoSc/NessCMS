<?php
	namespace Tags;

	class Handling {
		public static function getTags($condition = 'TRUE') {
			return \Basics\Handling::getList($condition, 'tags', 'Tags', 'Tag');

			// foreach ($datas as $key => $tagLoop)
				// $datas[$key]['posts_nbr'] = \Basics\Handling::countEntries('tags_relation', 'post_type = \'news\' AND tag_id = ' . $tagLoop['id']);

			// return $datas;
		}

		public static function createRelation($oldTagsIds, $tagsIds, $incomingId, $incomingType) {
			$db = \Basics\Site::getDB();
			$tempOldTagsIds = [];
			foreach ($oldTagsIds as $tagLoop)
				$tempOldTagsIds[] = (int) $tagLoop['id'];
			$oldTagsIds = &$tempOldTagsIds;
			$tagsIds = array_unique($tagsIds);

			if (empty($tagsIds) OR $tagsIds !== $oldTagsIds) {
				$request = $db->prepare('DELETE FROM tags_relation WHERE incoming_id = ? AND incoming_type = ?');
				$request->execute([$incomingId, $incomingType]);
			}
			if (!empty($tagsIds) AND $tagsIds !== $oldTagsIds) {
				foreach ($tagsIds as $tagLoop) {
					// Single::create($tagLoop, null, 'tag');
					$tempTag = (new Single($tagLoop))->getTag();

					if ($tempTag AND $tempTag['type'] !== 'category') {
						$request = $db->prepare('INSERT INTO tags_relation (id, tag_id, incoming_id, incoming_type) VALUES (?, ?, ?, ?)');
						$request->execute([\Basics\Strings::identifier(), $tagLoop, $incomingId, $incomingType]);
					}
					else
						trigger_error('Link\'s creation has failed. The tag may not exist.');
				}
			}
		}

		public static function tagAndGame($getGame = true, $id, $tagType = 'game') {
			if ($getGame) {
				$query = '	SELECT r.post_id id
							FROM tags t, tags_relation r
							WHERE t.type = ? AND r.tag_id = t.id AND r.incoming_type = ? AND r.tag_id = ?';
			}
			else {
				$query = '	SELECT t.id id
							FROM tags t, tags_relation r
							WHERE t.type = ? AND r.tag_id = t.id AND r.incoming_type = ? AND r.incoming_id = ?';
			}

			$request = \Basics\Site::getDB()->prepare($query);
			$request->execute([$tagType, 'games', $id]);
			$datas = $request->fetchAll(\PDO::FETCH_ASSOC);

			return $datas;
		}
	}
