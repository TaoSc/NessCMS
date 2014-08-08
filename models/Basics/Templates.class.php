<?php
	namespace Basics;

	class Templates {
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

		static function getImg($slug, $extension, $width, $height) {
			global $subDir;

			return $subDir . 'images/' . $slug . '-' . $width . 'x' . $height .  '.' . $extension;
		}

		static function pollAnswers($poll) {
			global $siteDir, $clauses;

			include $siteDir . 'views/Templates/pollAnswers.php';
		}

		static function smallUserBox($member) {
			global $siteDir, $subDir, $clauses;

			include $siteDir . 'views/Templates/smallUserBox.php';
		}

		static function comment($comment, $languageVerif, $hidden, $commentsTemplate = false) {
			global $siteDir, $linksDir, $language, $clauses, $currentMemberId;
			$commentAnswers = \Comments\Handling::getComments('parent_id = ' . $comment['id'], $languageVerif, $hidden, true);

			$lastCommentId = $comment['parent_id'];
			$comment['recursivity'] = 0;
			while ($lastCommentId) {
				$lastCommentId = (new \Comments\Single($lastCommentId))->getComment(false, false)['parent_id'];
				$comment['recursivity'] += 1;
			}

			include $siteDir . 'views/Templates/comment.php';
		}
	}