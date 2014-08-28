<?php
	namespace Categories;

	class Single {
		private $category;

		function __construct($id) {
			$this->category = new \Tags\Single($id, 'category');
		}

		function getCategory($lineJump = true) {
			return $this->category->getTag();
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
		}*/

		function deleteCateg() {
			if ($this->category AND $this->category['id'] != 1) {
				global $db;

				$request = $db->prepare('DELETE FROM languages_routing WHERE incoming_id = ? AND table_name = ?');
				$request->execute([$this->category['id'], 'categories']);

				$request = $db->prepare('DELETE FROM categories WHERE id = ?');
				$request->execute([$this->category['id']]);

				$request = $db->prepare('UPDATE posts SET category_id = 1 WHERE category_id = ?');
				$request->execute([$this->category['id']]);

				return true;
			}
			else
				return false;
		}
	}