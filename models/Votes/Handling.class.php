<?php
	namespace Votes;

	class Handling {
		static function number($postId, $tableName = 'posts', $state = 1) {
			global $db;

			$request = $db->prepare('SELECT COUNT(*) likes_nbr FROM votes WHERE table_name = ? AND post_id = ? AND state = ?');
			$request->execute([$tableName, $postId, $state]);

			return (int) $request->fetch(\PDO::FETCH_ASSOC)['likes_nbr'];
		}

		static function send($postId, $state = 1, $tableName = 'posts') {
			global $currentMemberId;

			if ($currentMemberId) {
				global $db;

				$request = $db->prepare('INSERT INTO votes(state, author_id, table_name, post_id, vote_date) VALUES(?, ?, ?, ?, NOW())');
				$request->execute([$state, $currentMemberId, $tableName, $postId]);

				return true;
			}
			else
				return false;
		}

		static function delete($postId, $tableName = 'posts') {
			global $currentMemberId;

			if ($currentMemberId) {
				global $db;

				$request = $db->prepare('DELETE FROM votes WHERE author_id = ? AND table_name = ? AND post_id = ?');
				$request->execute([$currentMemberId, $tableName, $postId]);

				return true;
			}
			else
				return false;
		}

		static function did($postId, $tableName = 'posts') {
			global $currentMemberId;

			if ($currentMemberId) {
				global $db;

				$request = $db->prepare('SELECT COUNT(*) total FROM votes WHERE author_id = ? AND table_name = ? AND post_id = ?');
				$request->execute([$currentMemberId, $tableName, $postId]);

				return $request->fetch(\PDO::FETCH_ASSOC)['total'] ? true : false;
			}
			else
				return false;
		}
	}