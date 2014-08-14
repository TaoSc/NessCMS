<?php
	namespace Posts;

	class Single {
		private $post;

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

		static function setViews($newsId, $reset = false) {
			global $db;

			if ($reset)
				$viewsNbr = 0;
			else {
				$request = $db->prepare('SELECT views FROM posts WHERE type = ? AND id = ?');
				$request->execute(['news', $newsId]);

				$viewsNbr = ++$request->fetch(\PDO::FETCH_ASSOC)['views'];
			}

			$request = $db->prepare('UPDATE posts SET views = ? WHERE type = ? AND id = ?');
			$request->execute([$viewsNbr, 'news', $newsId]);
		}
	}