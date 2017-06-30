<?php
	namespace Members;

	class Handling {
		public static function check($nickname, $slug, $firstName, $lastName, $email, $pwd, $nicknameTest = true, $birthDate = '0000-00-01', $namesTest = true) {
			if (!empty($birthDate) AND $birthDate !== '0000-00-00' AND !empty($nickname) AND !empty($email) AND !empty($pwd)) {
				$birthDateRegex = preg_match('#^([0-9]{4})-([0-9]{2})-([0-9]{2})$#', $birthDate);
				$emailRegex = preg_match('#^[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+\.[a-zA-Z]{2,4}$#', $email);
				$namesTestCond = $namesTest ? mb_strlen($lastName) >= 2 AND mb_strlen($firstName) >= 2 : true;

				if ($namesTestCond AND $birthDateRegex AND mb_strlen($nickname) >= 4 AND $emailRegex AND mb_strlen($pwd) >= 6) {
					if ($nicknameTest) {
						$db = \Basics\Site::getDB();

						$request = $db->prepare('SELECT id FROM members WHERE slug = ?');
						$request->execute([$slug]);
						$otherMbrsIds = $request->fetch(\PDO::FETCH_ASSOC)['id'];
						$request->closeCursor();

						$request = $db->prepare('SELECT id FROM members WHERE email = ?');
						$request->execute([$email]);
						$otherMbrsIds .= $request->fetch(\PDO::FETCH_ASSOC)['id'];
						$request->closeCursor();
					}
					else
						$otherMbrsIds = null;

					if (empty($otherMbrsIds) AND mb_substr_count($nickname, '@') === 0)
						return true;
					else
						return false;
				}
				else
					return false;
			}
			else
				return false;
		}

		public static function login($nickname, $pwd, $cookies = false) {
			global $clauses;

			if (preg_match('#^[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+\.[a-zA-Z]{2,4}$#', $nickname))
				$columnName = 'email';
			else
				$columnName = 'nickname';

			$request = \Basics\Site::getDB()->prepare('SELECT id, password FROM members WHERE ' . $columnName . ' = ? AND type_id != 3');
			$request->execute([$nickname]);
			$member = $request->fetch(\PDO::FETCH_ASSOC);
			$request->closeCursor();

			if (!$member)
				return error($clauses->get('invalid_user'));
			elseif (empty($member['id']) OR empty($nickname) OR empty($pwd) OR !empty(\Basics\site::session('member_id')))
				return false;
			elseif ($pwd === $member['password']) {
				if ($cookies) {
					\Basics\site::cookie('name', $nickname);
					\Basics\site::cookie('password', $member['password']);
				}
				\Basics\site::session('member_id', (int) $member['id']);

				return true;
			}
			else
				return false;
		}

		public static function logout() {
			global $topDir;

			session_destroy();
			setcookie(\Basics\Strings::slug(\Basics\Site::parameter('name')) . '_name', '', time(), $topDir, null, false, true);
			setcookie(\Basics\Strings::slug(\Basics\Site::parameter('name')) . '_password', '', time(), $topDir, null, false, true);

			return true;
		}

		public static function registration($nickname, $email, $pwd1, $pwd2, $cookies = false, $admin = false) {
			$nickname = htmlspecialchars($nickname);
			$slug = \Basics\Strings::slug($nickname);
			if (self::check($nickname, $slug, null, null, $email, $pwd2, true, '0000-00-01', false) AND $pwd1 === $pwd2) {
				$request = \Basics\Site::getDB()->prepare('
					INSERT INTO members (type_id, nickname, slug, email, password, registration)
					VALUES (?, ?, ?, ?, ?, NOW())
				');
				$request->execute([($admin ? 1 : \Basics\Site::parameter('default_users_type')), $nickname, $slug, htmlspecialchars($email), hash('sha256', $pwd2)]);

				if (self::login($nickname, hash('sha256', $pwd2), $cookies))
					return true;
				elseif (!$admin) {
					global $clauses;

					return error(stripslashes(eval($clauses->getMagic('login_fail'))));
				}
			}
			else
				return false;
		}
	}
