<?php
	namespace News;

	class Single {
		private $news;

		function __construct($id, $visible = true, $languageCheck = true) {
			$this->news = new \Posts\Single($id, 'news', $visible, $languageCheck);
		}

		function getNews() {
			$newsItself = $this->news->getPost();
			if ($newsItself) {
				global $clauses;

				$newsItself['content'] = $clauses->getDB('posts', $newsItself['id'], 'content');

				$newsItself['category'] = (new \Categories\Single($newsItself['category_id']))->getCategory(false);
				$newsItself['likes'] = \Votes\Handling::number($newsItself['id'], 'posts');
				$newsItself['dislikes'] = \Votes\Handling::number($newsItself['id'], 'posts', -1);
			}

			return $newsItself;
		}

		function setNews(...$traversableContent) {
			$editSucceed = $this->news->setPost(...$traversableContent);

			if ($editSucceed AND \Basics\Site::parameter('cache_enabled')) {
				global $cache;
				$newsItself = $this->news->getPost();

				$cache->delete('news/' . $newsItself['slug'], true);
			}

			return $editSucceed;
		}

		function deleteNews() {
			if ($this->news->deletePost()) {
				

				return true;
			}
			else
				return false;
		}

		static function create($title, $subTitle, $content, $categoryId, $tagsIds = null, $img, $slug = null, $visible = false, $priority = 'normal', $commentsEnabled = true) {
			if ($newsId = \Posts\Single::create($title, $subTitle, $content, $categoryId, $tagsIds, $img, $slug, $visible, $priority, $commentsEnabled)) {
				

				return $newsId;
			}
			else
				return false;
		}
	}