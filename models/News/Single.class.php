<?php
	namespace News;

	class Single extends \Posts\Single {
		protected $news;

		public function __construct($id, $visible = true, $languageCheck = true) {
			parent::__construct($id, 'news', $visible, $languageCheck);
			$this->news = &$this->post;

			if ($this->news) {
				global $currentMemberId, $rights;

				$this->news['removal_cond'] = ($rights['news_create'] AND (in_array($currentMemberId, $this->news['authors_ids']) OR $rights['admin_access']));
				$this->news['edit_cond'] = ($currentMemberId AND ((in_array($currentMemberId, $this->news['authors_ids']) AND $rights['news_edit']) OR $rights['admin_access']));
			}
		}

		public function getNews() {
			if (parent::getPost()) {
				global $clauses;

				$this->news['content'] = $clauses->getDB('posts', $this->news['id'], 'content');

				$this->news['category'] = (new \Categories\Single($this->news['category_id']))->getCategory(false);
				if ($this->news['votes']) {
					$this->news['likes'] = \Votes\Handling::number($this->news['id'], 'posts');
					$this->news['dislikes'] = \Votes\Handling::number($this->news['id'], 'posts', -1);
				}
			}

			return $this->news;
		}

		public function setNews(...$traversableContent) {
			if (!$this->news['edit_cond'])
				return false;

			global $rights;

			if (!$rights['news_publish'])
				$traversableContent[6] = $this->news['visible']; // Not perfect since this is not aware of the modifications history

			$editSucceed = parent::setPost(...$traversableContent);
			if ($editSucceed AND \Basics\Site::parameter('cache_enabled')) {
				global $cache;

				$cache->delete('news/' . $this->news['slug'], true);
			}

			return $editSucceed;
		}

		public function deleteNews() {
			return ($this->news['removal_cond']) ? parent::deletePost() : false;
		}

		public static function create($title, $subTitle, $content, $categoryId, $tagsIds = null, $img, $slug = null, $visible = false, $priority = 'normal', $comments = true, $votes = true, &$rights) {
			if (!$rights['news_create'])
				return false;

			if (!$rights['news_publish'])
				$visible = false;

			return parent::createAbstract($title, $subTitle, $content, $categoryId, $tagsIds, $img, $slug, $visible, $priority, $comments, $votes);
		}
	}
