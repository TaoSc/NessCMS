<?php
	if ($ajaxCheck) {
		if (isset($_POST['poll_radios']) AND (new Polls\Single($params[1]))->addVote($_POST['poll_radios'])) {
			$poll = (new Polls\Single($params[1]))->getPoll();

			ob_start();
			Basics\Templates::pollAnswers($poll);
			$pollAnswers = ob_get_clean();

			echo json_encode(['poll_participants' => Basics\Strings::plural($clauses->get('participants'), $poll['total_votes']), 'poll_answers' => $pollAnswers]);
		}
		else
			echo $clauses->get('polls_vote_fail');
	}
	else
		header('Location: ' . $linksDir . 'polls/' . $params[1]);
