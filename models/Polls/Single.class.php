<?php
	namespace Polls;

	class Single {
		private $poll;

		public function __construct($id) {
			$request = \Basics\Site::getDB()->prepare('SELECT id, answers, DATE(poll_date) date, TIME(poll_date) time, author_id FROM polls WHERE id = ?');
			$request->execute([$id]);
			$this->poll = $request->fetch(\PDO::FETCH_ASSOC);

			if (!empty($this->poll)) {
				global $currentMemberId;

				$this->poll['total_votes'] = \Basics\Handling::countEntries('polls_users', 'poll_id = ' . $this->poll['id']);
				$this->poll['answers'] = json_decode($this->poll['answers'], true);
				$this->poll['already_voted'] = (bool) \Basics\Handling::countEntries('polls_users', 'poll_id = ' . $this->poll['id'] . ' AND (user_id = ' . $currentMemberId . ' OR (user_id = 0 AND ip = \'' . \Basics\Handling::ipAddress() . '\'))');
			}
		}

		public function getPoll() {
			if ($this->poll) {
				global $clauses;

				$this->poll['question'] = $clauses->getDB('polls_questions', $this->poll['id'], 'name');
				$this->poll['time'] = \Basics\Dates::sexyTime($this->poll['time']);
				$this->poll['date'] = $this->poll['date'];

				foreach ($this->poll['answers'] as $key => $answerLoop) {
					$this->poll['answers'][$key]['name'] = $clauses->getDB('polls_answers', $answerLoop['id'], 'name');
					$this->poll['answers'][$key]['votes'] = $votes = \Basics\Handling::countEntries('polls_users', 'poll_id = ' . $this->poll['id'] . ' AND answer_id = ' . $answerLoop['id']);
					$this->poll['answers'][$key]['votes_percents'] = $votes ? $votes / $this->poll['total_votes'] * 100 : 0;
				}

				$this->poll['author'] = (new \Members\Single($this->poll['author_id']))->getMember(false);
			}

			return $this->poll;
		}

		public function deletePoll() {
			if ($this->poll) {
				$db = \Basics\Site::getDB();

				$request = $db->prepare('DELETE FROM polls WHERE id = ?');
				$request->execute([$this->poll['id']]);

				$request = $db->prepare('DELETE FROM languages_routing WHERE incoming_id = ? AND table_name = \'polls_questions\'');
				$request->execute([$this->poll['id']]);

				foreach ($this->poll['answers'] as $answerLoop) {
					$request = $db->prepare('DELETE FROM languages_routing WHERE incoming_id = ? AND table_name = \'polls_answers\'');
					$request->execute([$answerLoop['id']]);
				}

				return true;
			}
			else
				return false;
		}

		/*public static function createPoll($question, ) {
			if () {

			}
		}*/

		public function addVote($answerId) {
			if ($this->poll AND !$this->poll['already_voted'] AND \Basics\Handling::recursiveArraySearch((int) $answerId, $this->poll['answers']) !== false) {
				global $currentMemberId;

				$request = \Basics\Site::getDB()->prepare('INSERT INTO polls_users (poll_id, user_id, answer_id, ip) VALUES (?, ?, ?, ?)');
				$request->execute([$this->poll['id'], $currentMemberId, $answerId, \Basics\Handling::ipAddress()]);

				return true;
			}
			else
				return false;
		}
	}
