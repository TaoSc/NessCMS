<?php
	namespace Medias;

	class Image {
		private $image;

		function __construct($id) {
			global $subDir, $db;

			$request = $db->prepare('
				SELECT id, ext format, author_id, name, sizes, DATE(post_date) date, TIME(post_date) time, slug, type
				FROM medias
				WHERE id = ? AND type = \'images\'
			');
			$request->execute([$id]);
			$this->image = $request->fetch(\PDO::FETCH_ASSOC);

			if ($this->image) {
				$this->image['sizes'] = json_decode($this->image['sizes'], true);
				$this->image['sizes_nbr'] = count($this->image['sizes']);
				$this->image['address'] = $subDir . 'images/heroes/' . $this->image['slug'] . '.' . $this->image['format'];
			}
		}

		function getImage() {
			if ($this->image)
				$this->image['time'] = \Basics\Dates::sexyTime($this->image['time']);

			return $this->image;
		}

		function setImage($newName, $newSlug, $newSize, $newDescription) {
			if (!empty($newName) AND !empty($newSlug) AND $this->image) {
				if (\Basics\Handling::countEntrys('images', 'type = \'images\' AND slug = \'' . $newSlug . '\' AND id != ' . $this->image['id']))
					return false;
				else {
					global $db;

					if ($newSize) {
						foreach ($this->image['sizes'] as $sizeLoop) {
							if ($sizeLoop === $newSize)
								die('Cette image a déjà été rognée avec cette taille.');
						}

						$this->image['sizes'][] = $newSize;
						\Basics\Images::crop($this->image['address'], 'heroes/' . $this->image['slug'], [$newSize]);
					}

					$request = $db->prepare('UPDATE posts SET title = ?, slug = ?, content = ?, description = ? WHERE id = ?');
					$request->execute([$newName, $newSlug, json_encode($this->image['sizes']), $newDescription, $this->image['id']]);

					return true;
				}
			}
			else
				return false;
		}

		function delImage($size) {
			if ($this->image) {
				global $db;

				if ($size) {
					$size = explode('x', $size);
					if (in_array($size, $this->image['sizes'])) {
						global $siteDir;
						$sizeKey = array_search($size, $this->image['sizes']);

						unlink(\Basics\Templates::getImg('heroes/' . $this->image['slug'], $this->image['format'], $this->image['sizes'][$sizeKey][0], $this->image['sizes'][$sizeKey][1], false));

						unset($this->image['sizes'][$sizeKey]);

						$request = $db->prepare('UPDATE medias SET sizes = ? WHERE type = \'images\' AND id = ?');
						$request->execute([json_encode($this->image['sizes']), $this->image['id']]);
					}
					else
						return false;
				}
				else {
					$request = $db->prepare('DELETE FROM medias WHERE type = \'images\' AND id = ?');
					$request->execute([$this->image['id']]);

					foreach($this->image['files'] as $sizeLoop)
						unlink($sizeLoop);
				}

				return true;
			}
			else
				return false;
		}

		static function create($imageAddress, $imageName, $imageSizes) {
			global $siteDir, $db, $currentMemberId;

			$imageInfos = pathinfo($imageAddress);
			if (!isset($imageInfos['extension']) OR empty($imageInfos['extension']))
				return false;;

			$imageName = $imageName ?: $imageInfos['filename'];
			$imageSlug = \Basics\Strings::slug($imageName);
			if (\Basics\Handling::countEntrys('medias', 'type = \'images\' AND slug = \'' . $imageSlug . '\''))
				return false;;

			$imageIdentifier = \Basics\Strings::identifier();
			// $imageSizes[] = [100, 70];
			$imageExtension = \Basics\Images::crop($imageAddress, 'heroes/' . $imageSlug, $imageSizes);
			copy($imageAddress, $siteDir . 'images/heroes/' . $imageSlug . '.' . $imageExtension);

			$request = $db->prepare('
				INSERT INTO medias(id, ext, author_id, name, sizes, post_date, slug, type)
				VALUES(?, ?, ?, ?, ?, NOW(), ?, ?)
			');
			$request->execute([
				$imageIdentifier,
				$imageExtension,
				$currentMemberId,
				$imageName,
				json_encode($imageSizes),
				$imageSlug,
				'images'
			]);

			return \Basics\Handling::latestId('medias');
		}
	}