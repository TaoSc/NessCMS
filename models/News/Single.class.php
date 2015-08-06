<?php
	namespace News;

	class Single extends \Posts\Single {
		protected $news;

		function __construct($id, $visible = true, $languageCheck = true) {
			parent::__construct($id, 'news', $visible, $languageCheck);
			$this->news = &$this->post;
		}

		function getNews() {
			if (parent::getPost()) {
				global $clauses;

				$this->news['content'] = $clauses->getDB('posts', $this->news['id'], 'content');

				$this->news['category'] = (new \Categories\Single($this->news['category_id']))->getCategory(false);
				$this->news['likes'] = \Votes\Handling::number($this->news['id'], 'posts');
				$this->news['dislikes'] = \Votes\Handling::number($this->news['id'], 'posts', -1);
			}

			return $this->news;
		}

		function setNews(...$traversableContent) {
			$editSucceed = parent::setPost(...$traversableContent);

			if ($editSucceed AND \Basics\Site::parameter('cache_enabled')) {
				global $cache;

				$cache->delete('news/' . $this->news['slug'], true);
			}

			return $editSucceed;
		}

		function deleteNews() {
			return parent::deletePost();
		}

		static function create($title, $subTitle, $content, $categoryId, $tagsIds = null, $img, $slug = null, $visible = false, $priority = 'normal', $commentsEnabled = true) {
			return parent::createAbstract($title, $subTitle, $content, $categoryId, $tagsIds, $img, $slug, $visible, $priority, $commentsEnabled);
		}
	}