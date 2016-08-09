<?php
	namespace Comments;

	class Single {
		protected $comment;

		public function __construct($id) {
			$request = \Basics\Site::getDB()->prepare('
				SELECT id, author_id, parent_id, post_id, post_type, hidden, content, language, DATE(post_date) date, TIME(post_date) time,
				DATE(modif_date) modif_date, TIME(modif_date) modif_time
				FROM comments
				WHERE id = ?
			');
			$request->execute([$id]);
			$this->comment = $request->fetch(\PDO::FETCH_ASSOC);

			if ($this->comment) {
				global $currentMemberId, $rights;

				$this->comment['author_id'] = (int) $this->comment['author_id'];
				$this->comment['removal_cond'] = 
				$this->comment['edit_cond'] = ($currentMemberId AND (($currentMemberId === $this->comment['author_id'] AND $rights['comment_edit'] AND $this->comment['hidden'] != 2) OR $rights['comment_moderate']));
			}
		}

		public function getComment($lineJump = true, $parsing = true) {
			if ($this->comment AND $parsing) {
				global $language;

				$this->comment['time'] = \Basics\Dates::sexyTime($this->comment['time']);
				if ($this->comment['modif_date'])
					$this->comment['modif_time'] = \Basics\Dates::sexyTime($this->comment['modif_time']);
				$this->comment['content'] = htmlspecialchars($this->comment['content']);
				if ($lineJump)
					$this->comment['content'] = \Basics\Strings::bbCode(nl2br($this->comment['content'], false));

				$this->comment['author'] = (new \Members\Single($this->comment['author_id']))->getMember(false);
				$this->comment['language'] = (new \Basics\Languages($this->comment['language'], false))->getLanguage($language);
				$this->comment['likes'] = \Votes\Handling::number($this->comment['id'], 'comments');
				$this->comment['dislikes'] = \Votes\Handling::number($this->comment['id'], 'comments', -1);
				$this->comment['popularity'] = $this->comment['likes'] - $this->comment['dislikes'];
			}

			return $this->comment;
		}

		public function setComment($content, $hidden = false) {
			if ($this->comment AND !empty($content) AND !empty($content) AND $this->comment['edit_cond']) {
				$hidden = (int) $hidden;

				$request = \Basics\Site::getDB()->prepare('UPDATE comments SET content = ?, hidden = ? WHERE id = ?');
				$request->execute([$content, $hidden, $this->comment['id']]);

				return true;
			}
			else
				return false;
		}

		public function deleteComment($realRemoval = true) {
			if ($this->comment AND $this->comment['removal_cond']) {
				$db = \Basics\Site::getDB();

				if ($realRemoval) {
					$request = $db->prepare('DELETE FROM comments WHERE id = ?');
					$request->execute([$this->comment['id']]);

					$request = $db->prepare('UPDATE comments SET hidden = 2 WHERE parent_id = ?');
					$request->execute([$this->comment['id']]);
				}
				else {
					$request = $db->prepare('UPDATE comments SET hidden = 2 WHERE id = ? OR parent_id = ?');
					$request->execute([$this->comment['id'], $this->comment['id']]);
				}

				return true;
			}
			else
				return false;
		}

		public static function create($parentId, $postId, $postType, $content) {
			global $currentMemberId;
			$parentId = (int) $parentId;

			if ((\Basics\Site::parameter('anonymous_coms') OR $currentMemberId) AND !empty($content)) {
				global $language;

				$request = \Basics\Site::getDB()->prepare('INSERT INTO comments (author_id, ip, parent_id, post_id, post_type, hidden, content, language, post_date) VALUES (?, ?, ?, ?, ?, 0, ?, ?, NOW())');
				$request->execute([$currentMemberId, \Basics\Handling::ipAddress(), $parentId, $postId, $postType, $content, $language]);

				return true;
			}
			else
				return false;
		}
	}
