<?php
	namespace Basics;

	class Images {
		public static function crop($src, $dstUrl, $dstSizes = null) {
			if (!isset(pathinfo($src)['extension']))
				die('Error.');
			ini_set('memory_limit', '-1');
			set_time_limit(0);

			$extension = preg_replace('#\?.{1,}#i', null, mb_strtolower(pathinfo($src)['extension']));
			$pngFormat = $extension === 'png';
			$createFunction = 'imagecreatefrom' . ($extension === 'jpg' ? 'jpeg' : $extension);
			$dstSizes = (array) $dstSizes;

			if (!in_array($extension, ['png', 'jpg'], true))
				$extension = 'jpg';

			$src = $createFunction($src);
			if (!$src)
				die('Error.');

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

				if ($pngFormat) {
					imagealphablending($transfo, false);
					imagealphablending($dst, false);
				}

				$srcX = ($srcW - $transfoW) / 2;
				$srcY = ($srcH - $transfoH) / 2;

				imagecopyresampled($transfo, $src, 0, 0, $srcX, $srcY, $srcW, $srcH, $srcW, $srcH);
				imagecopyresampled($dst, $transfo, 0, 0, 0, 0, $dstSize[0], $dstSize[1], $transfoW, $transfoH);

				$finalUrl = Templates::getImg($dstUrl, $extension, $dstSize[0], $dstSize[1], false);

				if ($pngFormat) {
					imagesavealpha($dst, true);
					imagepng($dst, $finalUrl, 0);
				}
				else {
					imageinterlace($dst, true);
					imagejpeg($dst, $finalUrl, 100);
				}
			}

			return $extension;
		}
	}
