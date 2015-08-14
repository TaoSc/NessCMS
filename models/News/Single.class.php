<?php
	namespace News;

	class Single extends \Posts\Single {
		protected $news;

		function __construct($id, $visible = true, $languageCheck = true) {
			parent::__construct($id, 'news', $visible, $languageCheck);
			$this->news = &$this->post;

			if ($this->news) {
				global $currentMemberId, $rights;

				$this->news['create_cond'] = $rights['news_create'];
				$this->news['removal_cond'] = ($rights['news_create'] AND (in_array($currentMemberId, $this->news['authors_ids']) OR $rights['admin_access']));
				$this->news['edit_cond'] = ($currentMemberId AND ((in_array($currentMemberId, $this->news['authors_ids']) AND $rights['news_edit']) OR $rights['admin_access']));
			}
		}

		function getNews() {
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

		function setNews(...$traversableContent) {
			if ($this->news['edit_cond']) {
				global $rights;
				if (!$rights['news_publish'])
					$traversableContent[6] = $this->news['visible']; // Not perfect since this is not aware of the modification's history
				$editSucceed = parent::setPost(...$traversableContent);

				if ($editSucceed AND \Basics\Site::parameter('cache_enabled')) {
					global $cache;

					$cache->delete('news/' . $this->news['slug'], true);
				}

				return $editSucceed;
			}
			return false;
		}

		function deleteNews() {
			if ($this->news['removal_cond']) {
				return parent::deletePost();
			}
			else
				return false;
		}

		static function create($title, $subTitle, $content, $categoryId, $tagsIds = null, $img, $slug = null, $visible = false, $priority = 'normal', $comments = true, $votes = true) {
			if ($this->news['create_cond']) {
				global $rights;
				if (!$rights['news_publish'])
					$visible = false;

				return parent::createAbstract($title, $subTitle, $content, $categoryId, $tagsIds, $img, $slug, $visible, $priority, $comments, $votes);
			}
			else
				return false;
		}
	}