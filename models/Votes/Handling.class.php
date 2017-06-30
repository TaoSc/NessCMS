<?php
	namespace Votes;

	class Handling {
		public static function number($postId, $tableName = 'posts', $state = 1) {
			$request = \Basics\Site::getDB()->prepare('SELECT COUNT(*) likes_nbr FROM votes WHERE table_name = ? AND post_id = ? AND state = ?');
			$request->execute([$tableName, $postId, $state]);

			return (int) $request->fetch(\PDO::FETCH_ASSOC)['likes_nbr'];
		}

		public static function send($postId, $state = 1, $tableName = 'posts') {
			global $currentMemberId;

			if ($currentMemberId OR \Basics\Site::parameter('anonymous_votes')) {
				$request = \Basics\Site::getDB()->prepare('INSERT INTO votes (state, author_id, ip, table_name, post_id, vote_date) VALUES (?, ?, ?, ?, ?, NOW())');
				$request->execute([$state, $currentMemberId, \Basics\Handling::ipAddress(), $tableName, $postId]);

				return true;
			}
			else
				return false;
		}

		public static function delete($postId, $tableName = 'posts') {
			global $currentMemberId;

			if ($currentMemberId) {
				$request = \Basics\Site::getDB()->prepare('DELETE FROM votes WHERE author_id = ? AND table_name = ? AND post_id = ?');
				$request->execute([$currentMemberId, $tableName, $postId]);

				return true;
			}
			else
				return false;
		}

		public static function did($postId, $tableName = 'posts') {
			global $currentMemberId;

			if ($currentMemberId OR \Basics\Site::parameter('anonymous_votes')) {
				$db = \Basics\Site::getDB();

				if ($currentMemberId) {
					$request = $db->prepare('SELECT COUNT(*) total FROM votes WHERE author_id = ? AND table_name = ? AND post_id = ?');
					$request->execute([$currentMemberId, $tableName, $postId]);
				}
				else {
					$request = $db->prepare('SELECT COUNT(*) total FROM votes WHERE author_id = 0 AND ip = ? AND table_name = ? AND post_id = ?');
					$request->execute([\Basics\Handling::ipAddress(), $tableName, $postId]);
				}

				return $request->fetch(\PDO::FETCH_ASSOC)['total'] ? true : false;
			}
			else
				return false;
		}
	}
