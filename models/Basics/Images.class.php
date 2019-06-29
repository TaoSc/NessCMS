<?php
	namespace Basics;

	class Images {
		public static function crop($src, $dstUrl, $dstSizes = null) {
			ini_set('memory_limit', '-1');
			set_time_limit(0);

			$typeNb = exif_imagetype($src);
			if ($typeNb === false)
				die('File could not be opened.');
			$supportedTypes = [1 => 'gif', 2 => 'jpeg', 3 => 'png', 6 => 'bmp', 15 => 'wbmp', 16 => 'xbm', 18 => 'webp'];

			if (!isset($supportedTypes[$typeNb]))
				die('Image type not supported.');
			else
				$type = $supportedTypes[$typeNb];

			$saveToLossless = $type !== 'jpeg';
			$createFunction = 'imagecreatefrom' . $type;
			$dstSizes = (array) $dstSizes;

			if (!in_array($type, ['png', 'jpeg'], true))
				$type = 'png';

			$src = $createFunction($src);
			if (!$src)
				die('Error duplicating image.');

			$srcW = imagesx($src);
			$srcH = imagesy($src);
			$srcRatio = $srcH / $srcW;

			foreach ($dstSizes as $dstSize) {
				$dstSize[0] = $dstSize[0] ?: $srcW;
				$dstSize[1] = $dstSize[1] ?: $srcH;
				$dstRatio = $dstSize[1] / $dstSize[0];
				$dst = imagecreatetruecolor($dstSize[0], $dstSize[1]);

				if ($srcRatio > $dstRatio) {
					$transfoW = $srcW;
					$transfoH = $srcW * $dstRatio;
				}
				else {
					$transfoW = $srcH / $dstRatio;
					$transfoH = $srcH;
				}
				$transfo = imagecreatetruecolor($transfoW, $transfoH);

				if ($saveToLossless) {
					imagealphablending($transfo, false);
					imagealphablending($dst, false);
				}

				$srcX = ($srcW - $transfoW) / 2;
				$srcY = ($srcH - $transfoH) / 2;

				imagecopyresampled($transfo, $src, 0, 0, $srcX, $srcY, $srcW, $srcH, $srcW, $srcH);
				imagecopyresampled($dst, $transfo, 0, 0, 0, 0, $dstSize[0], $dstSize[1], $transfoW, $transfoH);

				$finalUrl = Templates::getImg($dstUrl, $type, $dstSize[0], $dstSize[1], false);

				if ($saveToLossless) {
					imagesavealpha($dst, true);
					imagepng($dst, $finalUrl, -1);
				}
				else {
					imageinterlace($dst, true);
					imagejpeg($dst, $finalUrl, 100);
				}
			}

			return $type;
		}
	}
