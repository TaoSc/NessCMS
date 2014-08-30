<?php
	$pollsList = Polls\Handling::getPolls();
	$tempPollsList = [];
	foreach ($pollsList as $pollLoop)
		$tempPollsList[] = ['label' => Basics\Strings::plural($clauses->get('votes'), $pollLoop['total_votes']),
							'text' => $pollLoop['question'],
							'link' => $linksDir . 'polls/' . $pollLoop['id']];
	$pollsList = &$tempPollsList;

	if ($currentMemberId)
		$createPollLink = 'admin/polls/edit/0';
	else
		$createPollLink = 'members/login/admin=2Fpolls=2F0';

	$pageTitle = $clauses->get('polls');
	$viewPath = 'polls/index';
	$breadcrumb = [['name' => 'polls']];