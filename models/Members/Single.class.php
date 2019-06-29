<?php
	namespace Members;

	class Single {
		private $member;

		public function __construct($id) {
			$request = \Basics\Site::getDB()->prepare('
				SELECT id, type_id, nickname, slug, first_name, last_name, email, password, avatar avatar_id, DATE(registration) reg_date, TIME(registration) reg_time, birth
				FROM members
				WHERE id = ?
			');
			$request->execute([$id]);
			$this->member = $request->fetch(\PDO::FETCH_ASSOC);

			if ($this->member)
				$this->member['id'] = (int) $this->member['id'];
		}

		public function getMember($biography = true) {
			if ($this->member) {
				global $clauses;

				if ($this->member['first_name'])
					$this->member['name'] = $this->member['first_name'] . ' ' .  $this->member['last_name'];
				else
					$this->member['name'] = false;

				if (!$this->member['avatar_id']) {
					$this->member['avatar']['slug'] = 'default';
					$this->member['avatar']['format'] = 'png';
				}
				else
					$this->member['avatar'] = (new \Media\Image($this->member['avatar_id']))->getImage();

				if ($this->member['birth']) {
					$this->member['age'] = \Basics\Dates::age($this->member['birth']);
					$this->member['birth'] = $this->member['birth'];
				}
				$this->member['registration']['date'] = $this->member['reg_date'];
				$this->member['registration']['time'] = \Basics\Dates::sexyTime($this->member['reg_time']);
				$this->member['type'] = (new Type($this->member['type_id']))->getType();
				if ($biography)
					$this->member['biography'] =  \Basics\Strings::bbCode(nl2br($clauses->getDB('members', $this->member['id'], 'biography'), false));
			}

			return $this->member;
		}

		public function setMember($nickname, $avatar) {
			$nicknameTest = $nickname !== $this->member['nickname'];
			$mailTest = false;
			$slug = \Basics\Strings::slug($nickname);

			if ($this->member AND \Members\Handling::check($nickname, $slug, $this->member['first_name'], $this->member['last_name'], $this->member['email'], 'password', $nicknameTest, '0000-00-01', false, $mailTest)) {
				if (!empty($avatar) AND empty($member['avatar_id'])) {
					$avatar = \Media\Image::create($avatar, $nickname, [[100, 100]], 'avatars');
				}
				elseif (empty($avatar) OR !$avatar = (new \Media\Image($this->member['avatar_id']))->updateImage($avatar)) {
					$avatar = $this->member['avatar_id'];
				}

				$request = \Basics\Site::getDB()->prepare('UPDATE members SET nickname = ?, avatar = ? WHERE id = ?');
				$request->execute([$nickname, $avatar, $this->member['id']]);

				return true;
			}
			else
				return false;
		}

		/*public function setMember($newPseudo, $newSubName, $newFamilyName, $newEmail, $newPwd, $newType, $newAvatar, $pwdCript = true) {
			$pseudoTest = $newPseudo !== $this->member['pseudo'];
			$pwdCript = $pwdCript ? sha1($newPwd) : $newPwd;
			$namesTest = !empty($newSubName) AND !empty($newFamilyName);

			if ($this->member AND \Members\Handling::check($newPseudo, $newSubName, $newFamilyName, $newEmail, $newPwd, $pseudoTest, '0000-00-01', $namesTest) AND !empty($newType)) {
				global $siteDir, $cache;
				if (empty($newAvatar)) {
					if ($this->member['img_id'] === 'default')
						$newAvatar = null;
					else
						$newAvatar = $this->member['img'];
				}
				else {
					$newAvatar = \Basics\Images::crop($newAvatar, 'avatars/' . $this->member['id'], [[100, 100]]);
					if (!$newAvatar)
						die('Une erreur est survenue lors de l\'envoi de votre avatar.');

					if ($this->member['img_id'] !== 'default' AND $newAvatar !== $this->member['img']) {
						unlink($siteDir . '/images/avatars/' . $this->member['id'] . '-100x100.' . $this->member['img']);
						$cache->clear();
					}
				}

				$request = \Basics\Site::getDB()->prepare('UPDATE members SET pseudo = ?, img = ?, first_name = ?, family_name = ?, email = ?, password = ?, type_id = ? WHERE id = ?');
				$request->execute([
					htmlspecialchars($newPseudo),
					$newAvatar,
					htmlspecialchars($newSubName),
					htmlspecialchars($newFamilyName),
					htmlspecialchars($newEmail),
					$pwdCript,
					htmlspecialchars($newType),
					$this->member['id']
				]);

				$cache->delete('members_profiles_' . $this->member['id'] . '.ctrl');

				return true;
			}
			else
				return false;
		}

		public function setBiography($newBiography) {
			if ($this->member AND !empty($newBiography)) {
				global $cache;

				$request = \Basics\Site::getDB()->prepare('UPDATE members SET biography = ? WHERE id = ?');
				$request->execute([htmlspecialchars($newBiography), $this->member['id']]);

				$cache->delete('members_profiles_' . $this->member['id'] . '.ctrl');

				return true;
			}
			else
				return false;
		}

		public function deleteMember() {
			if ($this->member) {
				$db = \Basics\Site::getDB();

				$this->deleteAvatar();

				$request = $db->prepare('DELETE FROM members WHERE id = ?');
				$request->execute([$this->member['id']]);

				$request = $db->prepare('UPDATE categories SET author_id = ? WHERE author_id = ?');
				$request->execute([1, $this->member['id']]);

				$request = $db->prepare('DELETE FROM comments WHERE author_id = ?');
				$request->execute([$this->member['id']]);

				$request = $db->prepare('DELETE FROM posts WHERE author_id = ?');
				$request->execute([$this->member['id']]);

				$request = $db->prepare('DELETE FROM likes WHERE author_id = ?');
				$request->execute([$this->member['id']]);

				return true;
			}
			else
				return false;
		}

		public function deleteAvatar() {
			if ($this->member) {
				global $siteDir, $cache;

				unlink($siteDir . '/images/avatars/' . $this->member['id'] . '-100x100.' . $this->member['img']);

				$request = \Basics\Site::getDB()->prepare('UPDATE members SET img = ? WHERE id = ?');
				$request->execute([null, $this->member['id']]);

				$cache->clear();

				return true;
			}
			else
				return false;
		}*/
	}
