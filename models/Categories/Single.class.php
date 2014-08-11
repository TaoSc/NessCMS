<?php
	namespace Categories;

	class Single {
		private $category;

		function __construct($id) {
			global $db;

			$request = $db->prepare('
				SELECT id, author_id
				FROM categories
				WHERE id = ?
			');
			$request->execute([$id]);
			$this->category = $request->fetch(\PDO::FETCH_ASSOC);
		}

		function getCategory($lineJump = true) {
			if ($this->category) {
				global $clauses;

				$this->category['slug'] = $clauses->getDB('categories', $this->category['id'], 'slug');
				$this->category['title'] = $clauses->getDB('categories', $this->category['id'], 'title');
			}

			return $this->category;
		}

		function getNews($offsetLimit = false) {
			return \News\Handling::getNews('category_id = ' . $this->category['id'], true, true, $offsetLimit);
		}

		/*function setCateg($newName, $newDesc, $newSlug = null) {
			if (!empty($newName) AND !empty($newDesc) AND $this->category) {
				$newSlug = \Basics\Strings::slug(empty($newSlug) ? $newName : $newSlug);

				if (\Basics\Management::countEntry('categories', 'slug = \'' . $newSlug . '\' AND id != ' . $this->category['id']))
					return false;
				else {
					global $db;

					$request = $db->prepare('UPDATE categories SET slug = ?, name = ?, description = ? WHERE id = ?');
					$request->execute([$newSlug, $newName, $newDesc, $this->category['id']]);

					return true;
				}
			}
			else
				return false;
		}

		function delCateg() {
			if ($this->category) {
				global $db;

				$request = $db->prepare('DELETE FROM categories WHERE id = ?');
				$request->execute([$this->category['id']]);

				$request = $db->prepare('UPDATE posts SET category_id = 3 WHERE category_id = ?');
				$request->execute([$this->category['id']]);

				return true;
			}
			else
				return false;
		}*/
	}