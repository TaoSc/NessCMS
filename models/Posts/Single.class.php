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
				SELECT id, visible, type, category_id, img img_id, authors_ids, priority, DATE(post_date) date, TIME(post_date) time,
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

				$this->post['img'] = (new \Medias\Image($this->post['img_id']))->getImage();
				$this->post['authors'] = [];
				foreach (json_decode($this->post['authors_ids'], true) as $memberLoop)
					$this->post['authors'][] = (new \Members\Single($memberLoop))->getMember(false);
			}

			return $this->post;
		}

		function deletePost() {
			if ($this->post) {
				global $db;

				$request = $db->prepare('DELETE FROM posts WHERE type = ? AND id = ?');
				$request->execute([$this->post['type'], $this->post['id']]);

				$request = $db->prepare('DELETE FROM languages_routing WHERE table_name = ? AND incoming_id = ?');
				$request->execute(['posts', $this->post['id']]);

				(new \Medias\Image($this->post['img_id']))->deleteImage();

				return true;
			}
			else
				return false;
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

		static function create($categoryId, $title, $subTitle, $content, $img, $slug = null, $visible = false, $type = 'news', $parseSlug = true) {
			if (!empty($categoryId) AND !empty($title) AND !empty($subTitle) AND !empty($content) AND !empty($img)) {
				global $language;
				if (empty($slug))
					$slug = $title;
				if ($parseSlug)
					$slug = \Basics\Strings::slug($slug);

				if (\Basics\Handling::countEntrys('languages_routing', 'language = \'' . $language . '\' AND table_name = \'posts\' AND column_name = \'slug\' AND value = \'' . $slug . '\''))
					return false;
				else {
					global $db, $currentMemberId;

					$img = \Medias\Image::create($img, $title, Single::$imgsSizes);

					$request = $db->prepare('
						INSERT INTO posts (visible, type, category_id, img, authors_ids, priority, post_date)
						VALUES (?, ?, ?, ?, ?, ?, NOW())
					');
					$request->execute([
						$visible,
						$type,
						$categoryId,
						$img,
						json_encode([$currentMemberId]),
						'normal'
					]);

					$postId = \Basics\Handling::latestId();
					// \Basics\Handling::idFromSlug($slug, 'posts', 'slug', false)

					$request = $db->prepare('
						INSERT INTO languages_routing (id, language, incoming_id, table_name, column_name, value)
						VALUES (?, ?, ?, \'posts\', \'title\', ?),
						(?, ?, ?, \'posts\', \'sub_title\', ?),
						(?, ?, ?, \'posts\', \'content\', ?),
						(?, ?, ?, \'posts\', \'slug\', ?),
						(?, ?, ?, \'posts\', \'availability\', 1)
					');
					$request->execute([
						\Basics\Strings::identifier(),
						$language,
						$postId,
						$title,

						\Basics\Strings::identifier(),
						$language,
						$postId,
						$subTitle,

						\Basics\Strings::identifier(),
						$language,
						$postId,
						$content,

						\Basics\Strings::identifier(),
						$language,
						$postId,
						$slug,

						\Basics\Strings::identifier(),
						$language,
						$postId
					]);

					return $postId;
				}
			}
			else
				return false;
		}
	}