<?php
	$pollsList = Polls\Handling::getPolls();

	$finalPollsList = [];
	foreach ($pollsList as $poll)
		$finalPollsList[] = ['label' => Basics\Strings::plural($clauses->get('votes'), $poll['total_votes']),
							 'text' => $poll['question'],
							 'link' => $linksDir . 'polls/' . $poll['id']];

	if ($currentMemberId)
		$createPollLink = 'admin/polls/edit/0';
	else
		$createPollLink = 'members/login/admin=2Fpolls=2Fedit=2F0';

	$pageTitle = $clauses->get('polls');
	$viewPath = 'polls/index';
	$breadcrumb = [['name' => 'polls']];