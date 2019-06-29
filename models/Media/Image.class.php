<?php
	namespace Media;

	class Image {
		private $image;

		public function __construct($id) {
			global $subDir;

			$request = \Basics\Site::getDB()->prepare('
				SELECT id, ext format, author_id, name, sizes, DATE(post_date) date, TIME(post_date) time, slug, type, location
				FROM media
				WHERE id = ? AND type = \'images\'
			');
			$request->execute([$id]);
			$this->image = $request->fetch(\PDO::FETCH_ASSOC);

			if ($this->image) {
				$this->image['sizes'] = json_decode($this->image['sizes'], true);
				$this->image['sizes_nbr'] = count($this->image['sizes']);
				$this->image['address'] = $subDir . 'images/' . $this->image['location'] . '/' . $this->image['slug'] . '.' . $this->image['format'];
			}
		}

		public function getImage() {
			if ($this->image)
				$this->image['time'] = \Basics\Dates::sexyTime($this->image['time']);

			return $this->image;
		}

		public function setImage($newName, $newSlug, $newSize) {
			if (!$this->image OR \Basics\Handling::countEntries('media', 'type = \'images\' AND slug = \'' . $newSlug . '\' AND id != \'' . $this->image['id'] .'\''))
				return false;


			if ($newSize) {
				foreach ($this->image['sizes'] as $sizeLoop) {
					if ($sizeLoop === $newSize)
						die('Error: cannot crop the image twice. Abort.');
				}

				$this->image['sizes'][] = $newSize;
				\Basics\Images::crop($this->image['address'], $this->image['location'] . '/' . $this->image['slug'], [$newSize]);
			}

			if ($newSlug !== $this->image['slug']) {
				global $siteDir;

				foreach ($this->image['sizes'] as $sizeKey) {
					rename(\Basics\Templates::getImg($this->image['location'] . '/' . $this->image['slug'], $this->image['format'], $sizeKey[0], $sizeKey[1], false),
					       \Basics\Templates::getImg($this->image['location'] . '/' . $newSlug, $this->image['format'], $sizeKey[0],$sizeKey[1], false));
				}

				rename($siteDir . 'images/' . $this->image['location'] . '/' . $this->image['slug'] . '.' . $this->image['format'], $siteDir . 'images/' . $this->image['location'] . '/' . $newSlug . '.' . $this->image['format']);
			}

			$request = \Basics\Site::getDB()->prepare('UPDATE media SET name = ?, slug = ?, sizes = ? WHERE id = ?');
			$request->execute([(empty($newName)) ? $this->image['name'] : $newName,
			                   (empty($newSlug)) ? $this->image['slug'] : $newSlug,
			                   json_encode($this->image['sizes']),
			                   $this->image['id']]);

			return true;
		}

		public function deleteImage($size = false) {
			if ($this->image) {
				$db = \Basics\Site::getDB();

				if ($size) {
					$size = explode('x', $size);
					if (in_array($size, $this->image['sizes'])) {
						$sizeKey = array_search($size, $this->image['sizes']);

						unlink(\Basics\Templates::getImg($this->image['location'] . '/' . $this->image['slug'], $this->image['format'], $this->image['sizes'][$sizeKey][0], $this->image['sizes'][$sizeKey][1], false));

						unset($this->image['sizes'][$sizeKey]);

						$request = $db->prepare('UPDATE media SET sizes = ? WHERE type = ? AND id = ?');
						$request->execute([json_encode($this->image['sizes']), 'images', $this->image['id']]);
					}
					else
						return false;
				}
				else {
					global $siteDir;

					$request = $db->prepare('DELETE FROM media WHERE type = ? AND id = ?');
					$request->execute(['images', $this->image['id']]);

					$filesList = glob($siteDir . 'images/' . $this->image['location'] . '/' . $this->image['slug'] . '*.' . $this->image['format']);
					foreach ($filesList as $sizeLoop)
						unlink($sizeLoop);
				}

				return true;
			}
			else
				return false;
		}

		public function updateImage($imageAddress) {
			global $siteDir;

			$imageInfos = pathinfo($imageAddress);
			if (!isset($imageInfos['extension']) OR empty($imageInfos['extension']))
				return false;

			$imageExtension = \Basics\Images::crop($imageAddress, $this->image['location'] . '/' . $this->image['slug'], $this->image['sizes']);

			if (!copy($imageAddress, $siteDir . 'images/' . $this->image['location'] . '/' . $this->image['slug'] . '.' . $imageExtension))
				return false;

			if ($imageExtension !== $this->image['format']) {
				$filesList = glob($siteDir . 'images/' . $this->image['location'] . '/' . $this->image['slug'] . '*.' . $this->image['format']);
				foreach ($filesList as $sizeLoop)
					unlink($sizeLoop);

				$request = \Basics\Site::getDB()->prepare('UPDATE media SET ext = ? WHERE type = ? AND id = ?');
				$request->execute([$imageExtension, 'images', $this->image['id']]);
			}
		}

		public static function create($imageAddress, $imageName, $imageSizes = [[100, 70]], $imageLocation = 'heroes') {
			global $siteDir, $currentMemberId;

			$imageInfos = pathinfo($imageAddress);
			if (!isset($imageInfos['extension']) OR empty($imageInfos['extension']))
				return false;

			$imageName = $imageName ?: $imageInfos['filename'];
			$imageSlug = \Basics\Strings::slug($imageName);
			if (\Basics\Handling::countEntries('media', 'type = \'images\' AND slug = \'' . $imageSlug . '\''))
				return false;

			$imageIdentifier = \Basics\Strings::identifier();
			$imageExtension = \Basics\Images::crop($imageAddress, $imageLocation . '/' . $imageSlug, $imageSizes);

			if (!copy($imageAddress, $siteDir . 'images/' . $imageLocation . '/' . $imageSlug . '.' . $imageExtension))
				return false;

			$request = \Basics\Site::getDB()->prepare('
				INSERT INTO media (id, ext, author_id, name, sizes, post_date, slug, type, location)
				VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)
			');
			$request->execute([
				$imageIdentifier,
				$imageExtension,
				$currentMemberId,
				$imageName,
				json_encode($imageSizes),
				$imageSlug,
				'images',
				$imageLocation
			]);

			return $imageIdentifier;
		}
	}
