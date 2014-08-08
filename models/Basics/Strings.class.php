<?php
	namespace Basics;

	class Strings {
		static function mb_ucfirst($string) {
			$strlen = mb_strlen($string);
			$firstChar = mb_substr($string, 0, 1);
			$then = mb_substr($string, 1, $strlen - 1);
			return mb_strtoupper($firstChar) . $then;
		}

		static function mb_lcfirst($string) {
			$strlen = mb_strlen($string);
			$firstChar = mb_substr($string, 0, 1);
			$then = mb_substr($string, 1, $strlen - 1);
			return mb_strtolower($firstChar) . $then;
		}

		static function mb_str_split($string) {
			return preg_split('~~u', $string, null, PREG_SPLIT_NO_EMPTY);
		}

		static function mb_strtr($string, $from, $to) {
			return str_replace(Strings::mb_str_split($from), Strings::mb_str_split($to), $string);
		}

		static function stripAccents($string) {
			return Strings::mb_strtr($string,
			'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
			'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		}

		static function slug($string) {
			$string = mb_strtolower(strip_tags(Strings::stripAccents($string)));
			$string = preg_replace('#[^a-z0-9]#', '-', $string);
			$string = preg_replace('#-+#', '-', trim($string, '-'));

			return $string;
		}

		static function cropTxt($string, $length = 25, $end = '…') {
			$finalString = null;
			for ($i = 0; $i < $length; $i++) {
				if (isset($string[$i]))
					$finalString .= mb_substr($string, $i, 1);
				else
					break;
			}

			if (mb_strlen($string) > $length)
				$finalString .= $end;

			return $finalString;
		}

		static function itemSearch($string, $correlationArray) {
			$string = str_split($string);

			foreach ($string as $character) {
				$expectedKey = array_search($character, $correlationArray, true);

				if ($expectedKey)
					$correlationArray[$expectedKey] = true;
				else {
					unset($correlationArray);
					$correlationArray = false;
					break;
				}
			}

			if (is_array($correlationArray)) {
				foreach ($correlationArray as $key => $element)
					$correlationArray[$key] = (int) $element;
			}

			return $correlationArray;
		}

		static function identifier($maxLength = 11) {
			$identifier = null;
			$possibilities = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
			for ($i = 0; $i < $maxLength; $i++)
				$identifier .= mb_substr($possibilities, mt_rand(0, mb_strlen($possibilities) - 1), 1);

			return $identifier;
		}

		static function plural($word, $quantity) {
			if ((int) $quantity === 1)
				return $quantity . ' ' . mb_substr($word, 0, -1);
			else
				return $quantity . ' ' . $word;
		}

		static function BBCode($text) {
			$parser = (new \JBBCode\Parser())->addCodeDefinitionSet(new \JBBCode\DefaultCodeDefinitionSet());

			return $parser->parse($text)->getAsHtml();
		}
	}