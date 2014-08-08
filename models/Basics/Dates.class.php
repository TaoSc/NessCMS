<?php
	// Toutes les dates doivent être traitées en utilisant le format américain.
	namespace Basics;

	class Dates {
		static function usDate($date) {
			if (mb_strpos($date, '-') === 4) {
				$date = explode('-', $date);
				return $date[1] . '-' . $date[2] . '-' . $date[0];
			}
			elseif (mb_strpos($date, '/')) {
				$date = explode('/', $date);
				return $date[1] . '-' . $date[0] . '-' . $date[2];
			}
			else
				return $date;
		}

		static function frDate($date) {
			if (mb_strpos($date, '-') === 4) {
				$date = explode('-', $date);
				return $date[2] . '/' . $date[1] . '/' . $date[0];
			}
			elseif (mb_strpos($date, '-') === 2) {
				$date = explode('-', $date);
				return $date[1] . '/' . $date[0] . '/' . $date[2];
			}
			else {
				$date = explode('/', $date);
				return $date[1] . '-' . $date[0] . '-' . $date[2];
			}
		}

		static function enDate($date) {
			if (mb_strpos($date, '/')) {
				$date = explode('/', $date);
				return $date[2] . '-' . $date[1] . '-' . $date[0];
			}
			elseif (mb_strpos($date, '-') === 2) {
				$date = explode('-', $date);
				return $date[2] . '-' . $date[0] . '-' . $date[1];
			}
			else {
				$date = explode('-', $date);
				return $date[1] . '-' . $date[2] . '-' . $date[0];
			}
		}

		static function countryDate($date) {
			global $language;

			$funcName = explode('-', $language)[1] . 'Date';
			return Dates::$funcName($date);
		}

		static function relativeTime($date, $time) {
			global $clauses;

			$interval = time() - strtotime(Dates::enDate($date) . ' ' . $time);

			if ($interval < 1)
				return $clauses->get('soon');

			$units = [
				12 * 30 * 24 * 60 * 60	=> 'years',
				30 * 24 * 60 * 60		=> 'months',
				24 * 60 * 60			=> 'days',
				60 * 60					=> 'hours',
				60						=> 'minutes',
				1						=> 'seconds'];

			foreach ($units as $seconds => $string) {
				$duration = $interval / $seconds;
				if ($duration >= 1) {
					$sentence = Strings::plural($clauses->get($string), round($duration));
					return stripslashes(eval($clauses->getMagic('relative_time')));
				}
			}
		}

		static function sexyDate($date, $cutDays = false, $today = false) {
			if (!mb_strpos($date, ':')) {
				global $language, $clauses;

				if (mb_strpos($date, '-') !== 2)
					$date = Dates::usDate($date);
				if ($today AND $date === date('m-d-Y'))
					return $clauses->get('today');

				$localDays = [$clauses->get('sunday'), $clauses->get('monday'), $clauses->get('tuesday'), $clauses->get('wednesday'),
							  $clauses->get('thursday'), $clauses->get('friday'), $clauses->get('saturday')];
				$localMonths = [$clauses->get('january'), $clauses->get('february'), $clauses->get('march'), $clauses->get('april'),
								$clauses->get('may'), $clauses->get('june'), $clauses->get('july'), $clauses->get('august'),
								$clauses->get('september'), $clauses->get('october'), $clauses->get('november'), $clauses->get('december')];
				$parsedDate = \DateTime::createFromFormat('m-d-Y', $date);

				if ($language === 'fr-fr') {
					$date = $localDays[$parsedDate->format('w')];
					if ($cutDays)
						$date = Strings::cropTxt($date, 3, '.');
					$date .= ' ' . $parsedDate->format('j') . ' ';
					$date .= $localMonths[$parsedDate->format('n') - 1];
					$date .= ' ' . $parsedDate->format('Y');
				}
				elseif ($language === 'en-us' OR $language === 'en-en') {
					$suffix = Dates::EnDaySuffix($parsedDate->format('j'));

					$date = $localDays[$parsedDate->format('w')];
					if ($cutDays)
						$date = Strings::cropTxt($date, 3, '.');
					$date .= ', ' . $localMonths[$parsedDate->format('n') - 1] . ' ';
					$date .= $parsedDate->format('j') . '<sup>' . $suffix . '</sup>, ';
					$date .= $parsedDate->format('Y');
				}
				return $date;
			}
			else {
				$date = explode(':', $date);
				return $date[0] . ' h ' . $date[1] . ' min';
			}
		}

		static function enDaySuffix($day) {
			switch ($day) {
				case 1: case 21: case 31: return 'st';
				case 2: case 22:          return 'nd';
				case 3: case 23:          return 'rd';
			}
			return 'th';
		}

		static function sexyTime($date) {
			global $clauses;

			return (new \DateTime($date))->format($clauses->get('time_format'));
		}

		static function age($birthDate) {
			list($month, $day, $year) = explode('-', Dates::usDate($birthDate));
			$now = (new \DateTime());
			$todayMonth = $now->format('n');
			$todayDay = $now->format('j');
			$todayYear = $now->format('Y');
			$years = $todayYear - $year;

			if ($todayMonth < $month)
				$years--;

			return $years;
		}
	}