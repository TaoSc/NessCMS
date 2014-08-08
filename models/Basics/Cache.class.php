<?php
	namespace Basics;

	class Cache {
		private $dirname;
		private $duration;
		private $memberExt;
		private $langExt;

		function __construct($dirname, $duration) {
			global $language, $currentMemberId;

			$this->dirname = $dirname . '/';
			$this->duration = $duration;
			$this->memberExt = '.' . $currentMemberId;
			$this->langExt = '.' . $language;
		}

		function get($fileName, $memberCheck = true, $gzDecode = true, $jsonDecode = false) {
			$file = $this->dirname . hash('sha256', $fileName . ($memberCheck ? $this->memberExt : null) . $this->langExt);

			if (file_exists($file)) {
				$file = file_get_contents($file);
				if ($gzDecode === true)
					$file = gzdecode($file);
				if ($jsonDecode === true)
					$file = json_decode($file, true);
				return $file;
			}
			else
				return false;
		}

		function exist($fileName, $memberCheck = true, $controllerCached = false) {
			$file = $this->dirname . hash('sha256', $fileName . ($memberCheck ? $this->memberExt : null) . $this->langExt);

			if (file_exists($file)) {
				$fileTime = (time() - filemtime($file)) / 60;
				if ($controllerCached)
					$fileTime /= 2;

				if ($fileTime > $this->duration)
					return $this->delete($file);
				else
					return true;
			}
			else
				return false;
		}

		function write($fileName, $content, $memberCheck = true, $jsonEncoder = false) {
			if ($jsonEncoder === true)
				$content = json_encode($content);

			file_put_contents($this->dirname . hash('sha256', $fileName . ($memberCheck ? $this->memberExt : null) . $this->langExt), gzencode($content, 5));

			return true;
		}

		function delete($fileName, $memberCheck = true) {
			$file = $this->dirname . hash('sha256', $fileName . ($memberCheck ? $this->memberExt : null) . $this->langExt);

			if (file_exists($file))
				unlink($file);
			return false;
		}

		function clear() {
			$files = glob($this->dirname . '*');
			foreach ($files as $file)
				unlink($file);
		}

		function getDirname() {
			return $this->dirname;
		}
	}