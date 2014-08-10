<?php
	namespace Members;

	class Handling {
		static function check($nickname, $slug, $firstName, $lastName, $email, $pwd, $nicknameTest = true, $birthDate = '0000-00-01', $namesTest = true) {
			global $db;

			if (!empty($birthDate) AND $birthDate !== '0000-00-00' AND !empty($nickname) AND !empty($email) AND !empty($pwd)) {
				$birthDateRegex = preg_match('#^([0-9]{4})-([0-9]{2})-([0-9]{2})$#', $birthDate);
				$emailRegex = preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email);
				$namesTestCond = $namesTest ? mb_strlen($lastName) >= 2 AND mb_strlen($firstName) >= 2 : true;

				if ($namesTestCond AND $birthDateRegex AND mb_strlen($nickname) >= 4 AND $emailRegex AND mb_strlen($pwd) >= 6) {
					if ($nicknameTest) {
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

		static function login($nickname, $pwd, $cookies = 'off') {
			global $db, $topDir, $clauses;

			if (preg_match('/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+\.[a-zA-Z]{2,4}/', $nickname))
				$columnName = 'email';
			else
				$columnName = 'nickname';

			$request = $db->prepare('SELECT id, password FROM members WHERE ' . $columnName . ' = ? AND type_id != 3');
			$request->execute([$nickname]);
			$member = $request->fetch(\PDO::FETCH_ASSOC);
			$request->closeCursor();

			if (!$member)
				return error($clauses->get('invalid_user'));
			elseif (empty($member['id']) OR empty($nickname) OR empty($pwd) OR !empty($_SESSION['id']))
				return false;
			elseif ($pwd === $member['password']) {
				if ($cookies === 'on') {
					setcookie('nesscms_name', $nickname, time() + 63072000, $topDir, null, false, true);
					setcookie('nesscms_password', $member['password'], time() + 63072000, $topDir, null, false, true);
				}
				$_SESSION['id'] = $member['id'];

				return true;
			}
			else
				return false;
		}

		static function logout() {
			global $topDir;

			session_destroy();
			setcookie('nesscms_name', '', time(), $topDir, null, false, true);
			setcookie('nesscms_password', '', time(), $topDir, null, false, true);

			return true;
		}

		static function registration($nickname, $email, $pwd1, $pwd2, $cookies) {
			$nickname = htmlspecialchars($nickname);
			$slug = \Basics\Strings::slug($nickname);
			if (Handling::check($nickname, $slug, null, null, $email, $pwd2, true, '0000-00-01', false) AND $pwd1 === $pwd2) {
				global $db, $clauses;

				$request = $db->prepare('
					INSERT INTO members(type_id, nickname, slug, email, password, registration)
					VALUES(2, ?, ?, ?, ?, NOW())
				');
				$request->execute([$nickname, $slug, htmlspecialchars($email), hash('sha256', $pwd2)]);

				if (Handling::login($nickname, hash('sha256', $pwd2), $cookies))
					return true;
				else
					return error(stripslashes(eval($clauses->getMagic('login_fail'))));
			}
			else
				return false;
		}
	}