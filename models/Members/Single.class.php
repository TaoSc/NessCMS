<?php
	namespace Members;

	class Single {
		private $member;

		function __construct($id) {
			global $db;

			$request = $db->prepare('
				SELECT id, type_id, nickname, slug, first_name, last_name, email, password, avatar, DATE(registration) reg_date, TIME(registration) reg_time, birth
				FROM members
				WHERE id = ?
			');
			$request->execute([$id]);
			$this->member = $request->fetch(\PDO::FETCH_ASSOC);
		}

		function getMember($lineJump = true) {
			if ($this->member) {
				global $clauses;

				if ($this->member['first_name'])
					$this->member['name'] = $this->member['first_name'] . ' ' .  $this->member['last_name'];
				else
					$this->member['name'] = false;
				if (!$this->member['avatar']) {
					$this->member['avatar_slug'] = 'default';
					$this->member['avatar'] = 'png';
				}
				else
					$this->member['avatar_slug'] = $this->member['slug'];
				if ($this->member['birth']) {
					$this->member['age'] = \Basics\Dates::age($this->member['birth']);
					$this->member['birth'] = $this->member['birth'];
				}
				$this->member['registration']['date'] = $this->member['reg_date'];
				$this->member['registration']['time'] = \Basics\Dates::sexyTime($this->member['reg_time']);
				$this->member['type'] = (new Type($this->member['type_id']))->getType();
				if ($lineJump)
					$this->member['biography'] =  \Basics\Strings::BBCode(nl2br($clauses->getDB('members', $this->member['id'], 'biography'), false));
			}

			return $this->member;
		}

		/*function setMember($newPseudo, $newSubName, $newFamilyName, $newEmail, $newPwd, $newType, $newAvatar, $pwdCript = true) {
			$pseudoTest = $newPseudo !== $this->member['pseudo'];
			$pwdCript = $pwdCript ? sha1($newPwd) : $newPwd;
			$namesTest = !empty($newSubName) AND !empty($newFamilyName);

			if ($this->member AND \Members\Handling::check($newPseudo, $newSubName, $newFamilyName, $newEmail, $newPwd, $pseudoTest, '0000-00-01', $namesTest) AND !empty($newType)) {
				global $siteDir, $db, $cache;
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

				$request = $db->prepare('UPDATE members SET pseudo = ?, img = ?, first_name = ?, family_name = ?, email = ?, password = ?, type_id = ? WHERE id = ?');
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

		function setBiography($newBiography) {
			if ($this->member AND !empty($newBiography)) {
				global $db, $cache;

				$request = $db->prepare('UPDATE members SET biography = ? WHERE id = ?');
				$request->execute([htmlspecialchars($newBiography), $this->member['id']]);

				$cache->delete('members_profiles_' . $this->member['id'] . '.ctrl');

				return true;
			}
			else
				return false;
		}

		function delMember() {
			if ($this->member) {
				global $db;

				$this->delAvatar();

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

		function delAvatar() {
			if ($this->member) {
				global $db, $siteDir, $cache;

				unlink($siteDir . '/images/avatars/' . $this->member['id'] . '-100x100.' . $this->member['img']);

				$request = $db->prepare('UPDATE members SET img = ? WHERE id = ?');
				$request->execute([null, $this->member['id']]);

				$cache->clear();

				return true;
			}
			else
				return false;
		}*/
	}