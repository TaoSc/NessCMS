<?php
	namespace Basics;

	class Templates {
		public static function basicHeaders() {
			global $linksDir, $subDir, $CMSVersion;

			echo '<meta charset="utf-8">', PHP_EOL,
				 '<meta http-equiv="X-UA-Compatible" content="IE=edge">', PHP_EOL,
				 '<meta name="generator" content="NessCMS ' . $CMSVersion . '">', PHP_EOL,
				 '<link rel="stylesheet" type="text/css" href="' . $subDir . 'css/bootstrap.min.css">', PHP_EOL,
				 '<script>var linksDir = \'' . $linksDir . '\';</script>', PHP_EOL,
				 '<script src="' . $subDir . 'js/jquery.min.js"></script>', PHP_EOL,
				 '<script src="' . $subDir . 'js/bootstrap.min.js"></script>' . PHP_EOL;
		}

		public static function textList($array) {
			$list = null;

			foreach ($array as $element) {
				$list .= '
					<span class="label label-default">' . $element['label'] . '</span>
					<a href="' . $element['link'] . '">' . $element['text'] . '</a>
					<hr>' . PHP_EOL;
			}

			echo trim($list, '<hr>' . PHP_EOL);
		}

		public static function dateTime($date, $time) {
			global $clauses;

			echo Dates::sexyDate($date, true, true) . ' ' . $clauses->get('at') . ' ' . Dates::sexyTime($date . ' ' . $time);
		}

		public static function getImg($slug, $extension, $width, $height, $relativeLoc = true) {
			if ($relativeLoc) {
				global $subDir;
				$dir = &$subDir;
			}
			else {
				global $siteDir;
				$dir = &$siteDir;
			}

			return $dir . 'images/' . $slug . '-' . $width . 'x' . $height .  '.' . $extension;
		}

		public static function pollAnswers($poll) {
			global $siteDir, $clauses, $theme;

			if ($poll['already_voted'])
				$poll['answers'] = Handling::twoDimSorting($poll['answers'], 'votes');

			include $siteDir . $theme['dir'] . 'views/Templates/pollAnswers.php';
		}

		public static function smallUserBox($member, $size = 'col-sm-5') {
			global $siteDir, $linksDir, $clauses, $theme;

			include $siteDir . $theme['dir'] . 'views/Templates/smallUserBox.php';
		}

		public static function postsList($postsArray, $emptyMessage = 'no_news') {
			global $siteDir, $linksDir, $clauses, $theme, $params, $foldersDepth;
			foreach ($postsArray as $key => $postLoop) {
				$height = 100;
				if ($postLoop['priority'] === 'important')
					$width = 750;
				elseif ($postLoop['priority'] === 'normal')
					$width = 250;
				elseif ($postLoop['priority'] === 'low') {
					$width = 200;
					$height = 70;
				}

				$postsArray[$key]['img_address'] = self::getImg('heroes/' . $postLoop['img']['slug'], $postLoop['img']['format'], $width, $height);
			}

			include $siteDir . $theme['dir'] . 'views/Templates/postsList.php';
		}

		public static function comment($comment, $languageCheck, $hidden, $commentsTemplate = false) {
			global $siteDir, $linksDir, $language, $clauses, $currentMemberId, $theme;
			$commentAnswers = \Comments\Handling::getComments('parent_id = ' . $comment['id'], $languageCheck, $hidden, true);

			$hasVoted = \Votes\Handling::did($comment['id'], 'comments');
			$voteBtnsCond = ($hasVoted OR (!$currentMemberId AND !Site::parameter('anonymous_votes')) OR $comment['hidden'] == 1);

			$lastCommentId = $comment['parent_id'];
			$comment['recursivity'] = 0;
			while ($lastCommentId) {
				$lastCommentId = (new \Comments\Single($lastCommentId))->getComment(false, false)['parent_id'];
				$comment['recursivity'] += 1;
			}

			include $siteDir . $theme['dir'] . 'views/Templates/comment.php';
		}
	}
