<?php
	namespace Comments;

	class Handling {
		static function getComments($condition = '0 = 0', $languageCheck = false, $hidden = true, $ascending = false, $offsetLimit = false, $idsOnly = false, $lineJump = true) {
			global $db, $language;
			if ($languageCheck)
				$condition .= ' AND language = \'' . $language . '\'';
			if ($hidden)
				$condition .= ' AND hidden < 2';
			$order = $ascending ? 'ASC' : 'DESC';
			if ($offsetLimit)
				$offsetLimit = ' LIMIT ' . $offsetLimit;

			$request = $db->query('SELECT id FROM comments WHERE ' . $condition . ' ORDER BY id ' . $order . $offsetLimit);
			$commentsIds = $request->fetchAll(\PDO::FETCH_ASSOC);

			if ($idsOnly)
				return $commentsIds;
			else {
				$comments = [];
				foreach ($commentsIds as $commentLoop) 
					$comments[] = (new Single($commentLoop['id']))->getComment($lineJump);
				return $comments;
			}
		}

		static function countComments($parentId, $postId, $postType, $languageCheck = true, $hidden = true) {
			global $language;
			$basicCondition = 'post_id = ' . $postId . ' AND post_type = \'' . $postType . '\'';
			$advancedCondition = null;
			if ($languageCheck)
				$advancedCondition .= ' AND language = \'' . $language . '\'';
			if ($hidden)
				$advancedCondition .= ' AND hidden < 2';

			$commentsIds = Handling::getComments($basicCondition . ' AND parent_id = ' . $parentId, $languageCheck, $hidden, false, false, true);
			$commentsNbr = $allCommentsNbr = count($commentsIds);
			foreach ($commentsIds as $commentLoop) {
				$repliesIds = Handling::getComments($basicCondition . ' AND parent_id = ' . $commentLoop['id'], $languageCheck, $hidden, false, false, true);
				$repliesNbr = count($repliesIds);

				if ($repliesNbr) {
					$allCommentsNbr += $repliesNbr;

					foreach ($repliesIds as $replyLoop)
						$allCommentsNbr += Handling::countComments($replyLoop['id'], $postId, $postType, $languageCheck, $hidden);
				}
			}

			return $allCommentsNbr;
		}

		static function view($postId, $postType = 'news', $actualPage = 1, $languageCheck = true, $ascending = false, $hidden = true, $commentsPerPage = 10) {
			global $siteDir, $linksDir, $clauses, $location, $language, $currentMemberId;
			$basicCondition = 'post_id = ' . $postId . ' AND post_type = \'' . $postType . '\'';
			$advancedCondition = null;
			if ($languageCheck)
				$advancedCondition .= ' AND language = \'' . $language . '\'';
			if ($hidden)
				$advancedCondition .= ' AND hidden < 2';

			$rootCommentsNbr = \Basics\Handling::countEntrys('comments', $basicCondition . $advancedCondition . ' AND parent_id = 0');
			$allCommentsNbr = Handling::countComments(0, $postId, $postType, $languageCheck, $hidden);

			$pages = (int) ceil($rootCommentsNbr / $commentsPerPage) ?: 1;
			$actualComments = $commentsPerPage * ($actualPage - 1);
			if ($actualPage > $pages OR !is_numeric($actualPage) OR $actualPage == 0)
				error();
			else {
				$offsetLimit = $actualComments . ', ' . $commentsPerPage;
				$comments = Handling::getComments($basicCondition . ' AND parent_id = 0', $languageCheck, $hidden, $ascending, $offsetLimit);
				$pageRootCommentsNbr = count($comments);

				include $siteDir . 'views/Templates/comments.php';
			}
		}
	}