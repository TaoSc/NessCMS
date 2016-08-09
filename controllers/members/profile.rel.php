<?php
	$member = (new Members\Single(Basics\Handling::idFromSlug($params[1], 'members')))->getMember();

	if (empty($member))
		error();
	else {
		$pageTitle = $clauses->get('o_quote') . $member['nickname'] . $clauses->get('c_quote') . ' - ' . $clauses->get('members');
		$viewPath = 'members/profile.rel';
		$breadcrumb = [
			['name' => 'members'],
			['name' => $clauses->get('o_quote') . $member['nickname'] . $clauses->get('c_quote')]
		];
	}
