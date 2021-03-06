<?php
	if ($params[2] === '0' AND $_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($tagId = \Tags\Single::create($_POST['name'], $_POST['type']))
			header('Location: ' . $linksDir . 'admin/tags/' . $tagId);
		else
			error($clauses->get('tag_create_fails'));
	}
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$tag = new Tags\Single($params[2]);

		if ($tag->setTag($_POST['name'], $_POST['type']))
			header('Refresh: 0');
		else
			error($clauses->get('tag_edit_fails'));
	}
	else {
		if ($params[2] === '0')
			$create = true;
		else {
			$create = false;
			$tag = (new Tags\Single($params[2]))->getTag();
		}

		if (empty($tag) AND !$create)
			error();
		else {
			$tagsTypes = Tags\Single::$types;

			if (!$create) {
				$btnsGroupMenu = [
					['link' => $linksDir . 'tags/' . $tag['slug'], 'name' => $clauses->get('show_more')],
					['link' => $linksDir . 'admin/tags/' . $tag['id'] . '/delete', 'name' => $clauses->get('delete'), 'type' => 'warning']
				];
			}

			$pageTitle = ($create ? $clauses->get('create') : $tag['name']) . ' - ' . $clauses->get('tags');
			$viewPath = 'tags/edit.rel';
		}
	}
