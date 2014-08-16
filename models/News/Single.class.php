<?php
	namespace News;

	class Single {
		private $news;
		private $languageCheck;

		function __construct($id, $visible = true, $languageCheck = true) {
			global $db;

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

		static function create($categId, $title, $subTitle, $content, $img, $tags = null, $slug = null, $visible = false, $parseSlug = true) {
			if (!empty($categId) AND !empty($title) AND !empty($subTitle) AND !empty($content) AND !empty($img)) {
				if ($newsId = \Posts\Single::create()) {
					echo 'lol';

					return $newsId;
				}
				else
					return false;
			}
			else
				return false;
		}
	}