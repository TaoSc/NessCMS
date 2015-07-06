<?php
	namespace Posts;

	class Single {
		private $post;
		private $languageCheck;
		static $imgsSizes = [
			[200, 70],
			[250, 100],
			[750, 100],
			[750, 400]
		];
		static $priorities = [
			'important',
			'normal',
			'low'
		];

		function __construct($id, $type = null, $visible = true, $languageCheck = true) {
			global $db;
			$this->languageCheck = $languageCheck;
			$condition = 'id = ?';
			if ($type)
				$condition .= ' AND type = \'' . $type . '\'';
			if ($visible)
				$condition .= ' AND visible = ' . $visible;

			$request = $db->prepare('
				SELECT id, visible, type, category_id, img img_id, authors_ids, priority, DATE(post_date) date, TIME(post_date) time, comments, views
				FROM posts
				WHERE ' . $condition
			);
			$request->execute([$id]);
			$this->post = $request->fetch(\PDO::FETCH_ASSOC);

			if (!empty($this->post) AND $languageCheck) {
				global $clauses;

				$this->post['availability'] = $clauses->getDB('posts', $this->post['id'], 'availability', false);
				$this->post['slug'] = $clauses->getDB('posts', $this->post['id'], 'slug', false);
				if ((!$this->post['availability'] OR !$this->post['slug']) AND $visible != false)
					$this->post = false;
			}
		}

		function getPost() {
			if ($this->post) {
				global $clauses;

				$this->post['default_language'] = $clauses->getDBLang('posts', 'availability', $this->post['id'], 'default');

				$this->post['title'] = $clauses->getDB('posts', $this->post['id'], 'title');
				$this->post['sub_title'] = $clauses->getDB('posts', $this->post['id'], 'sub_title');

				$this->post['time'] = \Basics\Dates::sexyTime($this->post['time']);

				$this->post['img'] = (new \Medias\Image($this->post['img_id']))->getImage();
				$this->post['authors'] = [];
				foreach (json_decode($this->post['authors_ids'], true) as $memberLoop)
					$this->post['authors'][] = (new \Members\Single($memberLoop))->getMember(false);
				$this->post['comments_nbr'] = \Comments\Handling::countComments(0, $this->post['id'], 'posts', $this->languageCheck);
			}

			return $this->post;
		}

		function setPost($title, $subTitle, $content, $categoryId, $img, $visible, $availability, $priority, $comments) {
			$slug = \Basics\Strings::slug($title);
			$slugBeing = \Basics\Handling::idFromSlug($slug, 'posts', 'slug', false);
			if ($this->post AND !empty($categoryId) AND !empty($slug) AND !empty($subTitle) AND !empty($content) AND in_array($priority, Single::$priorities) AND (!$slugBeing OR $slugBeing === $this->post['id'])) {
				global $db, $clauses, $language;

				if ($clauses->getDBLang('posts', 'availability', $this->post['id'], 'default') === $language)
					$availability = 'default';

				$clauses->setDB('posts', $this->post['id'], true, ['title', $title], ['sub_title', $subTitle], ['content', $content], ['slug', $slug], ['availability', $availability]);

				if (empty($img))
					$img = $this->post['img_id'];
				else
					$img = \Medias\Image::create($img, $title, Single::$imgsSizes);

				$request = $db->prepare('UPDATE posts SET category_id = ?, img = ?, visible = ?, priority = ?, comments = ? WHERE id = ?');
				$request->execute([$categoryId, $img, $visible, $priority, $comments, $this->post['id']]);

				return true;
			}
			else
				return false;
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

			return true;
		}

		static function create($categoryId, $title, $subTitle, $content, $img, $slug = null, $visible = false, $commentsEnabled = true, $type = 'news', $parseSlug = true) {
			if (!empty($categoryId) AND !empty($title) AND !empty($subTitle) AND !empty($content) AND !empty($img)) {
				if (empty($slug))
					$slug = $title;
				if ($parseSlug)
					$slug = \Basics\Strings::slug($slug);

				if (\Basics\Handling::idFromSlug($slug, 'posts', 'slug', false))
					return false;
				else {
					global $db, $currentMemberId, $clauses;

					$img = \Medias\Image::create($img, $title, Single::$imgsSizes);

					$request = $db->prepare('
						INSERT INTO posts (visible, type, category_id, img, authors_ids, priority, post_date, comments)
						VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)
					');
					$request->execute([
						$visible,
						$type,
						$categoryId,
						$img,
						json_encode([$currentMemberId]),
						'normal',
						$commentsEnabled
					]);

					$postId = \Basics\Handling::latestId();

					/*$request = $db->prepare('
						INSERT INTO languages_routing (id, language, incoming_id, table_name, column_name, value)
						VALUES (?, ?, ?, \'posts\', \'title\', ?),
						(?, ?, ?, \'posts\', \'sub_title\', ?),
						(?, ?, ?, \'posts\', \'content\', ?),
						(?, ?, ?, \'posts\', \'slug\', ?),
						(?, ?, ?, \'posts\', \'availability\', \'default\')
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
					]);*/
					$clauses->setDB('posts', $postId, false, ['title', $title], ['sub_title', $subTitle], ['content', $content], ['slug', $slug], ['availability', 'default']);

					return $postId;
				}
			}
			else
				return false;
		}
	}