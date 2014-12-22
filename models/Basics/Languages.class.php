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
			$this->language = array_merge($this->language, $this->getDB('languages', $this->language['id'], '*', true, false, $originLanguage));
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

		function getDB($tableName, $index, $columnsName = '*', $errorRecovery = true, $getId = false, $targetLanguage = null) {
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
					if ($recoveredColumns = $this->getDB($tableName, $index, $columnsName, false, $getId, Site::parameter('default_language')))
						return $recoveredColumns;
					elseif ($recoveredColumns = $this->getDB($tableName, $index, $columnsName, false, $getId, false))
						return $recoveredColumns;
					else
						return false;// return [$tableName . '[' . $index . '] ' . $columnsName];
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

		function setDB($tableName, $index, ...$keyValuePairs) {
			global $db, $language;

			$SQLLoop = null;
			$PDOExecute = [];
			foreach ($keyValuePairs as $keyValue) {
				$SQLLoop .= '(?, ?, ?, ?, ?, ?),';
				$PDOExecute[] = \Basics\Strings::identifier();
				$PDOExecute[] = $language;
				$PDOExecute[] = $index;
				$PDOExecute[] = $tableName;
				$PDOExecute[] = $keyValue[0];
				$PDOExecute[] = $keyValue[1];
			}
			$SQLLoop = trim($SQLLoop, ',');

			$request = $db->prepare('INSERT INTO languages_routing (id, language, incoming_id, table_name, column_name, value) VALUES ' . $SQLLoop);
			$request->execute($PDOExecute);
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