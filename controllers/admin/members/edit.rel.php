<?php
	$memberObj = new Members\Single($params[2]);
	$member = $memberObj->getMember(false);

	if (empty($member) OR (!$rights['admin_access'] AND $currentMemberId !== $member['id']))
		error();
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($memberObj->setMember($_POST['nickname'], $_POST['avatar'])) {
			Basics\site::session('member', (new Members\Single(Basics\site::session('member_id')))->getMember(false));

			header('Refresh: 0');
		}
		else
			error($clauses->get('member_edit_fails'));
	}
	else {
		$btnsGroupMenu[] = ['link' => $linksDir . 'members/' . $member['slug'] . '/', 'name' => $clauses->get('view_profile')];
		$btnsGroupMenu[] = ['link' => $linksDir . 'admin/members/' . $member['id'] . '/delete', 'name' => $clauses->get('delete'), 'type' => 'warning'];

		$pageTitle = $member['nickname'] . ' - ' . $clauses->get('members');
		$viewPath = 'members/edit.rel';
	}
