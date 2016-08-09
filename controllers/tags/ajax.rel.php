<?php
	if ($ajaxCheck) {
		if (isset($_GET['query'])) {
			// Nothing yet :/
		}
		$tagsList = Tags\Handling::getTags('type = \'' . $params[1] . '\'');
		$cleanTagsList = [];
		foreach ($tagsList as $tagLoop)
			$cleanTagsList[] = ['id' => $tagLoop['id'], 'name' => $tagLoop['name']];
		echo json_encode($cleanTagsList);
	}
	else
		error();
