<?php
	namespace News;

	class Single {
		private $news;
		private $languageCheck;

		function __construct($id, $visible = true, $languageCheck = true) {
			$this->languageCheck = $languageCheck;
			$this->news = new \Posts\Single($id, 'news', $visible, $languageCheck);
		}

		function getNews() {
			$newsItself = $this->news->getPost();
			if ($newsItself) {
				global $clauses;

				$newsItself['content'] = $clauses->getDB('posts', $newsItself['id'], 'content');

				$newsItself['category'] = (new \Categories\Single($newsItself['category_id']))->getCategory(false);
				$newsItself['comments_nbr'] = \Comments\Handling::countComments(0, $newsItself['id'], 'posts', $this->languageCheck);
			}

			return $newsItself;
		}

		function deleteNews() {
			if ($this->news->deletePost()) {
				

				return true;
			}
			else
				return false;
		}

		static function create($categoryId, $title, $subTitle, $content, $img, $tags = null, $slug = null, $visible = false, $commentsEnabled = true) {
			if ($newsId = \Posts\Single::create($categoryId, $title, $subTitle, $content, $img, $slug, $visible, $commentsEnabled)) {
				

				return $newsId;
			}
			else
				return false;
		}
	}