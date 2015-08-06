<?php
	namespace Categories;

	class Single extends \Tags\Single {
		protected $category;

		function __construct($id) {
			parent::__construct($id, 'category');
			$this->category = &$this->tag;
		}

		function getCategory(...$params) {
			return parent::getTag(...$params);
		}

		function getNews($offsetLimit = false) {
			return \News\Handling::getNews('category_id = ' . $this->category['id'], true, true, $offsetLimit);
		}

		function deleteTag() {
			$inheritedMethod = parent::deleteTag();

			if ($inheritedMethod) {
				global $db;

				$request = $db->prepare('UPDATE posts SET category_id = 1 WHERE category_id = ?');
				$request->execute([$this->category['id']]);
			}

			return $inheritedMethod;
		}
	}