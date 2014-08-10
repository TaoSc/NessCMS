<?php
	$poll = (new Polls\Single($params[1]))->getPoll();

	if (empty($poll))
		error();
	elseif ($ajaxCheck)
		Basics\Templates::pollAnswers($poll);
	else {
		$pageTitle = $clauses->get('o_quote') . Basics\Strings::cropTxt($poll['question'], 20) . $clauses->get('c_quote') . ' - ' . $clauses->get('polls');
		$viewPath = 'polls/poll.rel';
		$breadcrumb = [
			['name' => 'polls', 'link' => 'polls/'],
			['name' => $clauses->get('o_quote') . $poll['question'] . $clauses->get('c_quote')]
		];
	}