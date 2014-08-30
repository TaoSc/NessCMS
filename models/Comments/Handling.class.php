<?php
	namespace Comments;

	class Handling {
		static function getComments($condition = 'TRUE', $languageCheck = false, $hidden = true, $ascending = false, $offsetLimit = false, $idsOnly = false, $lineJump = true) {
			global $language;
			if ($languageCheck)
				$condition .= ' AND language = \'' . $language . '\'';
			if ($hidden)
				$condition .= ' AND hidden < 2';

			return \Basics\Handling::getList($condition, 'comments', 'Comments', 'Comment', $offsetLimit, $idsOnly, $ascending, $lineJump);
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

		static function view($postId, $postType = 'news', $actualPage = 1, $languageCheck = true, $order = false, $hidden = true, $commentsPerPage = false) {
			global $siteDir, $linksDir, $clauses, $location, $language, $currentMemberId, $theme;
			$basicCondition = 'post_id = ' . $postId . ' AND post_type = \'' . $postType . '\'';
			$advancedCondition = null;
			if ($languageCheck)
				$advancedCondition .= ' AND language = \'' . $language . '\'';
			if ($hidden)
				$advancedCondition .= ' AND hidden < 2';
			if (!$commentsPerPage)
				$commentsPerPage = \Basics\Site::parameter('coms_per_page');

			$rootCommentsNbr = \Basics\Handling::countEntries('comments', $basicCondition . $advancedCondition . ' AND parent_id = 0');
			$allCommentsNbr = Handling::countComments(0, $postId, $postType, $languageCheck, $hidden);

			$pages = (int) ceil($rootCommentsNbr / $commentsPerPage) ?: 1;
			$actualComments = $commentsPerPage * ($actualPage - 1);
			if ($actualPage > $pages OR !is_numeric($actualPage) OR $actualPage == 0)
				error();
			else {
				$offsetLimit = $actualComments . ', ' . $commentsPerPage;
				if ($order > 1)
					$order = $sortNeeded = 1;
				$comments = Handling::getComments($basicCondition . ' AND parent_id = 0', $languageCheck, $hidden, $order, $offsetLimit);
				if (isset($sortNeeded))
					$comments = \Basics\Handling::twoDimSorting($comments, 'popularity');
				$pageRootCommentsNbr = count($comments);

				include $siteDir . $theme['dir'] . 'views/Templates/comments.php';
			}
		}
	}