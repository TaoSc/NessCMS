<?php
	namespace Posts;

	class Single {
		private $post;
		static $imgsSizes = [
			[250, 100],
			[750, 100],
			[750, 400]
		];

		function __construct($id, $type, $visible = true, $languageCheck = true) {
			global $db;

			$request = $db->prepare('
				SELECT id, visible, type, category_id, img, authors_ids, priority, DATE(post_date) date, TIME(post_date) time,
				DATE(modif_date) modif_date, TIME(modif_date) modif_time, views
				FROM posts
				WHERE id = ? AND type = ?' . ($visible ? ' AND visible = ' . $visible : null)
			);
			$request->execute([$id, $type]);
			$this->post = $request->fetch(\PDO::FETCH_ASSOC);

			if (!empty($this->post) AND $languageCheck) {
				global $clauses;

				$this->post['availability'] = $clauses->getDB('posts', $this->post['id'], 'availability', false, false);
				$this->post['slug'] = $clauses->getDB('posts', $this->post['id'], 'slug', false, false);
				if (!$this->post['availability'] OR !$this->post['slug'])
					$this->post = false;
			}
		}

		function getPost() {
			if ($this->post) {
				global $clauses;

				$this->post['title'] = $clauses->getDB('posts', $this->post['id'], 'title');
				$this->post['sub_title'] = $clauses->getDB('posts', $this->post['id'], 'sub_title');

				$this->post['time'] = \Basics\Dates::sexyTime($this->post['time']);
				if ($this->post['modif_date'])
					$this->post['modif_time'] = \Basics\Dates::sexyTime($this->post['modif_time']);

				$this->post['img'] = (new \Medias\Image($this->post['img']))->getImage();
				$this->post['authors'] = [];
				foreach (json_decode($this->post['authors_ids'], true) as $memberLoop)
					$this->post['authors'][] = (new \Members\Single($memberLoop))->getMember(false);
			}

			return $this->post;
		}

		static function setViews($postId, $reset = false) {
			global $db;

			if ($reset)
				$viewsNbr = 0;
			else {
				$request = $db->prepare('SELECT views FROM posts WHERE type = ? AND id = ?');
				$request->execute(['news', $postId]);

				$viewsNbr = ++$request->fetch(\PDO::FETCH_ASSOC)['views'];
			}

			$request = $db->prepare('UPDATE posts SET views = ? WHERE type = ? AND id = ?');
			$request->execute([$viewsNbr, 'news', $postId]);
		}

		static function create($categId, $title, $subTitle, $content, $img, $slug = null, $visible = false, $type = 'news', $parseSlug = true) {
			if (!empty($categId) AND !empty($title) AND !empty($subTitle) AND !empty($content) AND !empty($img)) {
				$slug = empty($slug) ? $title : $slug;
				if ($parseSlug)
					$slug = \Basics\Strings::slug($slug);

				if (\Basics\Handling::countEntrys('posts', 'type = \'' . $type . '\' AND slug = \'' . $slug . '\''))
					return false;
				else {
					global $db, $currentMemberId;

					$img = \Medias\Image::create($img, $title, Single::$imgsSizes);

					$request = $db->prepare('
						INSERT INTO posts(category_id, img, author_id, title, sub_title, description, content, post_date, slug, visible, type)
						VALUES(?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)
					');
					$request->execute([
						$categId,
						$img,
						json_encode([$currentMemberId]),
						$title,
						$subTitle,
						$description,
						$content,
						$slug,
						$visible,
						$type
					]);

					return (new Single(\Basics\Handling::idFromSlug($slug, 'posts', 'slug', false)))->getPost()['id'];
				}
			}
			else
				return false;
		}
	}