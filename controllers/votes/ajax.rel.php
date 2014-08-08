<?php
	if ($ajaxCheck AND $_POST['vote_state'] AND $currentMemberId) {
		$alreadyVoted = Votes\Handling::did($params[2], $params[1]);

		if ($_POST['vote_state'] === 'strip' AND $alreadyVoted) {
			$noError = true;
			Votes\Handling::delete($params[2], $params[1]);
		}
		elseif ($_POST['vote_state'] !== 'strip' AND !$alreadyVoted) {
			$noError = true;

			if ($_POST['vote_state'] === 'up')
				$voteState = +1;
			elseif ($_POST['vote_state'] === 'down')
				$voteState = -1;
			else
				$noError = false;

			Votes\Handling::send($params[2], $voteState, $params[1]);
		}

		if (isset($noError) AND $noError)
			echo json_encode(['up' => Votes\Handling::number($params[2], $params[1]), 'down' => Votes\Handling::number($params[2], $params[1], -1)]);
		else
			error();
	}
	else
		error();