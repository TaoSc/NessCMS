<?php
	$rightsNames = Members\Type::$rights;

	if ($params[2] === '0' AND $_SERVER['REQUEST_METHOD'] === 'POST') {
		$rightsArray = [];
		foreach ($rightsNames as $rightName)
			$rightsArray[$rightName] = isset($_POST[$rightName]) ? true : 0;

		if ($typeId = \Members\Type::create($_POST['name'], $rightsArray))
			header('Location: ' . $linksDir . 'admin/members-types/' . $typeId);
		else
			error($clauses->get('members_type_create_fails'));
	}
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$type = new Members\Type($params[2]);

		$rightsArray = [];
		foreach ($rightsNames as $rightName)
			$rightsArray[$rightName] = isset($_POST[$rightName]) ? true : 0;

		if ($type->setType($_POST['name'], $rightsArray))
			header('Refresh: 0');
		else
			error($clauses->get('members_type_edit_fails'));
	}
	else {
		if ($params[2] === '0') {
			$create = true;
			$rightsArray = [];
			foreach ($rightsNames as $rightName)
				$rightsArray[$rightName] = false;
		}
		else {
			$create = false;
			$type = new Members\Type($params[2]);

			// A little bit heavy
			$rightsOfTheType = $type->getRights();
			$rightsArray = [];
			foreach ($rightsNames as $rightName) {
				if (isset($rightsOfTheType[$rightName]) AND $rightsOfTheType[$rightName] === true)
					$rightsArray[$rightName] = true;
				else
					$rightsArray[$rightName] = false;
			}

			$type = $type->getType();
		}

		if (empty($type) AND !$create)
			error();
		else {
			if (!$create) {
				$btnsGroupMenu = [
					['link' => $linksDir . 'members/types/' . $type['slug'], 'name' => $clauses->get('show_more')],
					['link' => $linksDir . 'admin/members-types/' . $type['id'] . '/delete', 'name' => $clauses->get('delete'), 'type' => 'warning']
				];
			}

			$pageTitle = ($create ? $clauses->get('create') : $type['name']) . ' - ' . $clauses->get('members_types');
			$viewPath = 'members-types/edit.rel';
		}
	}
