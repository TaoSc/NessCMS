<?php
	namespace Posts;

	abstract class Single {
		protected $post;
		protected $languageCheck;
		public static $imgSizes = [
			[200, 70],
			[250, 100],
			[750, 100],
			[750, 400]
		];
		public static $priorities = [
			'important',
			'normal',
			'low'
		];

		public function __construct($id, $type = null, $visible = true, $languageCheck = true) {
			$db = \Basics\Site::getDB();
			$this->languageCheck = $languageCheck;
			$condition = 'id = ?';
			if ($type)
				$condition .= ' AND type = \'' . $type . '\'';
			if ($visible)
				$condition .= ' AND visible = ' . $visible;

			$request = $db->prepare('
				SELECT id, visible, type, category_id, img img_id, authors_ids,
				priority, DATE(post_date) date, TIME(post_date) time, comments, votes, views
				FROM posts
				WHERE ' . $condition
			);
			$request->execute([$id]);
			$this->post = $request->fetch(\PDO::FETCH_ASSOC);

			if (!empty($this->post)/* AND $languageCheck*/) {
				global $clauses;

				$this->post['availability'] = $clauses->getDB('posts', $this->post['id'], 'availability', false);
				$this->post['slug'] = $clauses->getDB('posts', $this->post['id'], 'slug', false);

				if ((!$this->post['availability'] OR !$this->post['slug']) AND $visible != 0)
					$this->post = false;
				else {
					$request = $db->prepare('
						SELECT t.id id
						FROM tags t
						INNER JOIN tags_relation r
						ON r.tag_id = t.id
						WHERE r.incoming_id = ? AND r.incoming_type = ?
						ORDER BY r.id
					');
					$request->execute([$this->post['id'], 'posts']);
					$this->post['raw_tags'] = $request->fetchAll(\PDO::FETCH_ASSOC);
					$this->post['authors_ids'] = json_decode($this->post['authors_ids'], true);
				}
			}
		}

		public function getPost() {
			if ($this->post) {
				global $clauses;

				$this->post['default_language'] = $clauses->getDBLang('posts', 'availability', $this->post['id'], 'default');

				$this->post['title'] = $clauses->getDB('posts', $this->post['id'], 'title');
				$this->post['sub_title'] = $clauses->getDB('posts', $this->post['id'], 'sub_title');

				$this->post['time'] = \Basics\Dates::sexyTime($this->post['time']);

				$this->post['img'] = (new \Media\Image($this->post['img_id']))->getImage();
				$this->post['authors'] = [];
				foreach ($this->post['authors_ids'] as $memberLoop)
					$this->post['authors'][] = (new \Members\Single($memberLoop))->getMember(false);
				$this->post['comments_nbr'] = \Comments\Handling::countComments(0, $this->post['id'], 'posts', $this->languageCheck);

				if ($this->post['raw_tags']) {
					$condition = null;
					foreach ($this->post['raw_tags'] as $element)
						$condition .= ' OR id = ' . $element['id'];
					$condition = trim($condition, ' OR ');
					$this->post['tags'] = \Tags\Handling::getTags($condition);
				}
				else
					$this->post['tags'] = [];
			}

			return $this->post;
		}

		public function setPost($title, $subTitle, $content, $categoryId, $tagsIds, $img, $visible, $availability, $priority, $comments, $votes) {
			$slug = \Basics\Strings::slug($title);
			$slugBeing = \Basics\Handling::idFromSlug($slug, 'posts', 'slug', false);

			if ($this->post AND !empty($slug) AND (!$slugBeing OR $slugBeing === $this->post['id']) AND !empty($subTitle) AND !empty($content) AND !empty($categoryId) AND \Basics\Handling::countEntries('tags', 'id = ' . $categoryId . ' AND type = \'category\'') AND in_array($priority, Single::$priorities)) {
				global $clauses, $language;

				if ($clauses->getDBLang('posts', 'availability', $this->post['id'], 'default') === $language)
					$availability = 'default';

				$clauses->setDB('posts', $this->post['id'], true, ['title', $title], ['sub_title', $subTitle], ['content', $content], ['slug', $slug], ['availability', $availability]);

				if (empty($img) OR !$img = (new \Media\Image($this->post['img_id']))->updateImage($img))
					$img = $this->post['img_id'];

				if ($clauses->getDBLang('posts', 'availability', $this->post['id'], 'default') == $language AND $img == $this->post['img_id'] AND $slug !== $this->post['slug']) {
					(new \Media\Image($this->post['img_id']))->setImage($title, $slug, null); // if the image slug is already taken nothing will change for it, the error is silenced.
				}

				\Tags\Handling::createRelation($this->post['raw_tags'], json_decode($tagsIds), $this->post['id'], 'posts');

				$request = \Basics\Site::getDB()->prepare('UPDATE posts SET category_id = ?, img = ?, visible = ?, priority = ?, comments = ?, votes = ? WHERE id = ?');
				$request->execute([$categoryId, $img, (int) $visible, $priority, $comments, $votes, $this->post['id']]);

				return true;
			}
			else
				return false;
		}

		public function deletePost() {
			if ($this->post) {
				$db = \Basics\Site::getDB();

				$request = $db->prepare('DELETE FROM posts WHERE type = ? AND id = ?');
				$request->execute([$this->post['type'], $this->post['id']]);

				$request = $db->prepare('DELETE FROM languages_routing WHERE table_name = ? AND incoming_id = ?');
				$request->execute(['posts', $this->post['id']]);

				$request = $db->prepare('DELETE FROM tags_relation WHERE incoming_type = ? AND incoming_id = ?');
				$request->execute(['posts', $this->post['id']]);

				(new \Media\Image($this->post['img_id']))->deleteImage();

				return true;
			}
			else
				return false;
		}

		public function setViews($reset = false, $type = 'news') {
			if ($this->post) {
				$db = \Basics\Site::getDB();

				if ($reset)
					$viewsNbr = 0;
				else {
					$request = $db->prepare('SELECT views FROM posts WHERE type = ? AND id = ?');
					$request->execute([$type, $this->post['id']]);

					$viewsNbr = ++$request->fetch(\PDO::FETCH_ASSOC)['views'];
				}

				$request = $db->prepare('UPDATE posts SET views = ? WHERE type = ? AND id = ?');
				$request->execute([$viewsNbr, $type, $this->post['id']]);

				return true;
			}
			else
				return false;
		}

		static protected function createAbstract($title, $subTitle, $content, $categoryId, $tagsIds = null, $img, $slug = null, $visible = false, $priority = 'normal', $comments = true, $votes = true, $type = 'news', $parseSlug = true) {
			if (!empty($subTitle) AND !empty($content) AND !empty($categoryId) AND !empty($tagsIds) AND !empty($img) AND \Basics\Handling::countEntries('tags', 'id = ' . $categoryId . ' AND type = \'category\'') AND in_array($priority, Single::$priorities)) {
				if (empty($slug))
					$slug = $title;
				if ($parseSlug)
					$slug = \Basics\Strings::slug($slug);

				if (\Basics\Handling::idFromSlug($slug, 'posts', 'slug', false) OR empty($slug))
					return false;
				else {
					global $currentMemberId, $clauses;

					$img = \Media\Image::create($img, $title, Single::$imgSizes);

					$request = \Basics\Site::getDB()->prepare('
						INSERT INTO posts (visible, type, category_id, img, authors_ids, priority, post_date, comments, votes)
						VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)
					');
					$request->execute([(int) $visible, $type, $categoryId, $img, json_encode([$currentMemberId]), $priority, $comments, $votes]);

					$postId = \Basics\Handling::latestId();

					\Tags\Handling::createRelation([], json_decode($tagsIds), $postId, 'posts');
					$clauses->setDB('posts', $postId, false, ['title', $title], ['sub_title', $subTitle], ['content', $content], ['slug', $slug], ['availability', 'default']);

					return $postId;
				}
			}
			else
				return false;
		}
	}
