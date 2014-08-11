<?php
	namespace Basics;

	class Templates {
		static function basicHeaders() {
			global $topDir, $subDir;

			echo '<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<link rel="stylesheet" type="text/css" href="' . $subDir . 'css/bootstrap.min.css">
				<script>var topDir = ' . $topDir . ';</script>
				<script src="' . $subDir . 'js/jquery.min.js"></script>
				<script src="' . $subDir . 'js/bootstrap.min.js"></script>';
		}

		static function textList($array) {
			$list = null;

			foreach ($array as $element) {
				$list .= '
					<span class="label label-default">' . $element['label'] . '</span>
					<a href="' . $element['link'] . '">' . $element['text'] . '</a>
					<hr>' . PHP_EOL;
			}

			echo trim($list, '<hr>' . PHP_EOL);
		}

		static function dateTime($date, $time) {
			global $clauses;

			echo Dates::sexyDate($date, true, true) . ' ' . $clauses->get('at') . ' ' . Dates::sexyTime($date . ' ' . $time);
		}

		static function getImg($slug, $extension, $width, $height, $relativeLoc = true) {
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

		static function pollAnswers($poll) {
			global $siteDir, $clauses, $theme;

			if ($poll['already_voted'])
				$poll['answers'] = Handling::twoDimSorting($poll['answers'], 'votes');

			include $siteDir . $theme['dir'] . 'views/Templates/pollAnswers.php';
		}

		static function smallUserBox($member) {
			global $siteDir, $linksDir, $clauses, $theme;

			include $siteDir . $theme['dir'] . 'views/Templates/smallUserBox.php';
		}

		static function comment($comment, $languageCheck, $hidden, $commentsTemplate = false) {
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