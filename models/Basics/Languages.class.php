<?php
	namespace Basics;

	class Languages {
		private $language;
		private $file;

		function __construct($language, $retrieveFile = true) {
			global $db;

			$request = $db->prepare('SELECT id, code, enabled FROM languages WHERE code = ?');
			$request->execute([$language]);
			$this->language = $request->fetch(\PDO::FETCH_ASSOC);

			if (!empty($this->language) AND $retrieveFile) {
				global $siteDir, $theme;

				if (!file_exists($siteDir . 'languages/' . $this->language['code'] . '.txt'))
					$this->language['code'] = Site::parameter('default_language');
				$this->file = file($siteDir . 'languages/' . $this->language['code'] . '.txt', FILE_SKIP_EMPTY_LINES);

				if (file_exists($siteDir . $theme['dir'] . 'languages/' . $this->language['code'] . '.txt'))
					$this->file = array_merge($this->file, file($siteDir . $theme['dir'] . 'languages/' . $this->language['code'] . '.txt', FILE_SKIP_EMPTY_LINES));
			}
		}

		function getLanguage($originLanguage = false) {
			$this->language = array_merge($this->language, $this->getDBClean('languages', $this->language['id'], '*', true, false, $originLanguage));
			$this->language['name'] = $this->language['lang_name'] . ' (' . $this->language['country_name'] . ')';

			return $this->language;
		}

		function get($mark) {
			foreach ($this->file as $value) {
				$line = explode('= ', $value, 2);

				if (trim($line[0]) === $mark) {
					$finalSentence = trim($line[1], "\t\n\r\0\x0B");

					if ($finalSentence === '' AND $this->language['code'] !== Site::parameter('default_language'))
						return (new Languages(Site::parameter('default_language')))->get($mark);
					else
						return str_replace('NULL', null, $finalSentence);
				}
			}

			return $mark;
		}

		function getMagic($mark) {
			return 'return "' . addslashes($this->get($mark)) . '";';
		}

		function getDB($tableName, $index, $columnsName = '*', $originLanguageTemp = false, $errorRecovery = true, $getId = false) {
			global $db;
			if ($originLanguageTemp === false)
				$originLanguage = $this->language['code'];
			else
				$originLanguage = $originLanguageTemp;
			if ($getId === false) {
				$from = 'incoming_id';
				$to = 'value';
			}
			else {
				$from = 'value';
				$to = 'incoming_id';
			}
			$condition = 'table_name = ? AND ' . $from . ' = ?';
			if ($originLanguage !== null)
				$condition .= ' AND language = \'' . $originLanguage . '\'';
			if ($columnsName !== '*')
				$condition .= ' AND column_name = \'' . $columnsName . '\'';

			$request = $db->prepare('SELECT * FROM languages_routing WHERE ' . $condition);
			$request->execute([$tableName, $index]);
			$columns = $request->fetchAll(\PDO::FETCH_ASSOC);

			if (empty($columns)) {
				if ($originLanguageTemp === false AND $originLanguage !== Site::parameter('default_language') AND $errorRecovery)
					return $this->getDB($tableName, $index, $columnsName, Site::parameter('default_language'));
				else
					return false;
			}
			// elseif ($originLanguage === null)
				// return $columns;
			elseif (count($columns) > 1) {
				$newColumns = [];
				foreach ($columns as $columnsElem)
					$newColumns[$columnsElem['column_name']] = $columnsElem[$to];
				return $newColumns;
			}
			else
				return $columns[0][$to];
		}

		function getDBClean($tableName, $index, $columnsName = '*', $errorRecovery = true, $getId = false, $targetLanguage = null) {
			global $db;
			if ($targetLanguage === null)
				$targetLanguage = $this->language['code'];
			if ($getId) {
				$from = 'value';
				$to = 'incoming_id';
			}
			else {
				$from = 'incoming_id';
				$to = 'value';
			}
			$conditions = 'table_name = ? AND ' . $from . ' = ?';
			if ($columnsName !== '*')
				$conditions .= ' AND column_name = \'' . $columnsName . '\'';
			if ($targetLanguage !== false)
				$conditions .= ' AND language = \'' . $targetLanguage . '\'';

			$request = $db->prepare('SELECT * FROM languages_routing WHERE ' . $conditions);
			$request->execute([$tableName, $index]);
			$columns = $request->fetchAll(\PDO::FETCH_ASSOC);

			if (empty($columns)) {
				if ($targetLanguage !== false AND $errorRecovery) {
					if ($recoveredColumns = $this->getDBClean($tableName, $index, $columnsName, false, $getId, Site::parameter('default_language')))
						return $recoveredColumns;
					elseif ($recoveredColumns = $this->getDBClean($tableName, $index, $columnsName, false, $getId, false))
						return $recoveredColumns;
					else
						return [$tableName . '[' . $index . '] ' . $columnsName];
				}
				else
					return false;
			}
			elseif (count($columns) > 1) {
				$newColumns = [];
				foreach ($columns as $columnsElem)
					$newColumns[$columnsElem['column_name']] = $columnsElem[$to];
				return $newColumns;
			}
			else
				return $columns[0][$to];
		}

		function getDBLang($tableName, $columnName, $index, $value) {
			global $db;

			$request = $db->prepare('SELECT language FROM languages_routing WHERE table_name = ? AND column_name = ? AND incoming_id = ? AND value = ?');
			$request->execute([$tableName, $columnName, $index, $value]);

			return $request->fetch(\PDO::FETCH_ASSOC)['language'];
		}

		static function getLanguages($condition = 'TRUE', $originLanguage = false, $codesOnly = false) {
			return Handling::getList($condition, 'languages', 'Basics', 'Language', false, $codesOnly, false, [$originLanguage], false);
		}
	}