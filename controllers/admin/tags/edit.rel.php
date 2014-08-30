<?php
	if ($params[2] === '0' AND $_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($tagId = \Tags\Single::create($_POST['name'], $_POST['type']))
			header('Location: ' . $linksDir . 'admin/tags/' . $tagId);
		else
			error($clauses->get('tags_create_fails'));
	}
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$tag = (new Tags\Single($params[2]))->getTag();

		if ($tag->setTag())
			header('Location: ' . $linksDir . 'admin/tags/' . $tag['id']);
		else
			error('tag_edit_fails');
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
			$types = [
				'tag' => 'Libellé',
				'game' => 'Jeu',
				'developer' => 'Développeur',
				'publisher' => 'Éditeur',
				'system' => 'Plateforme / OS',
				'gender' => 'Genre',
				'category' => $clauses->get('category')
			];

			if (!$create) {
				$btnsGroupMenu = [
					['link' => $linksDir . 'tags/' . $tag['slug'], 'name' => $clauses->get('show_more')],
					['link' => $linksDir . 'admin/tags/' . $tag['id'] . '/delete', 'name' => $clauses->get('delete'), 'type' => 'warning']
				];

				$types[$tag['type'] . '" selected="selected'] = $types[$tag['type']];
				unset($types[$tag['type']]);
			}

			$pageTitle = ($create ? $clauses->get('create') : $tag['name']) . ' - ' . $clauses->get('tags');
			$viewPath = 'tags/edit.rel';
		}
	}