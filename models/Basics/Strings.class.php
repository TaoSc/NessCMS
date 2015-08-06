<?php
	namespace Basics;

	class Strings {
		static function mb_ucfirst($string) {
			return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1, mb_strlen($string) - 1);
		}

		static function mb_lcfirst($string) {
			return mb_strtolower(mb_substr($string, 0, 1)) . mb_substr($string, 1, mb_strlen($string) - 1);
		}

		static function mb_str_split($string) {
			return preg_split('~~u', $string, null, PREG_SPLIT_NO_EMPTY);
		}

		static function mb_strtr($string, $from, $to) {
			return str_replace(self::mb_str_split($from), self::mb_str_split($to), $string);
		}

		static function stripAccents($string) {
			return self::mb_strtr($string,
			'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
			'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		}

		static function slug($string) {
			$string = mb_strtolower(strip_tags(self::stripAccents($string)));
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

			$finalString = trim($finalString);
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
			global $clauses;

			if (((int) $quantity === 1 OR $quantity === false) AND !in_array(mb_strtolower($word), explode(',', mb_strtolower($clauses->get('plural_exceptions')))))
				$word = mb_substr($word, 0, -1);

			if ($quantity !== false)
				return $quantity . ' ' . $word;
			else
				return $word;
		}

		static function BBCode($text) {
			return (new \JBBCode\Parser())->addCodeDefinitionSet(new \JBBCode\DefaultCodeDefinitionSet())->parse($text)->getAsHtml();
		}
	}