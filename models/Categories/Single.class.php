<?php
	namespace Categories;

	class Single extends \Tags\Single {
		protected $category;

		public function __construct($id) {
			parent::__construct($id, 'category');
			$this->category = &$this->tag;
		}

		public function getCategory(...$params) {
			return parent::getTag(...$params);
		}

		public function getNews($offsetLimit = false) {
			return \News\Handling::getNews('category_id = ' . $this->category['id'], true, true, $offsetLimit);
		}

		public function deleteTag() {
			$inheritedMethod = parent::deleteTag();

			if ($inheritedMethod) {
				$request = \Basics\Site::getDB()->prepare('UPDATE posts SET category_id = 1 WHERE category_id = ?');
				$request->execute([$this->category['id']]);
			}

			return $inheritedMethod;
		}
	}
