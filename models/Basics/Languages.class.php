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
				global $siteDir;

				if (!file_exists($siteDir . 'languages/' . $this->language['code'] . '.txt'))
					$this->language['code'] = Site::parameter('default_language');
				$this->file = file($siteDir . 'languages/' . $this->language['code'] . '.txt', FILE_SKIP_EMPTY_LINES);
			}
		}

		function getLanguage($originLanguage = false) {
			$this->language = array_merge($this->language, $this->getDB('languages', $this->language['id'], '*', $originLanguage));
			$this->language['name'] = $this->language['lang_name'] . ' (' . $this->language['country_name'] . ')';

			return $this->language;
		}

		function get($mark) {
			foreach ($this->file as $value) {
				$line = explode('= ', $value, 2);

				if (trim($line[0]) === $mark) {
					$finalSentence = trim($line[1], "\t\n\r\0\x0B");

					if ($finalSentence === '')
						return (new Languages(Site::parameter('default_language')))->get($mark);
					else
						return str_replace('NULL', null, $finalSentence);
				}
			}
		}

		function getMagic($mark) {
			return 'return "' . addslashes($this->get($mark)) . '";';
		}

		function getDB($tableName, $id, $columnsName = '*', $originLanguageTemp = false, $errorRecovery = true) {
			global $db;
			if ($originLanguageTemp === false)
				$originLanguage = $this->language['code'];
			else
				$originLanguage = $originLanguageTemp;
			$condition = 'table_name = ? AND incoming_id = ? AND language = ?';
			if ($columnsName !== '*')
				$condition .= ' AND column_name = \'' . $columnsName . '\'';

			$request = $db->prepare('SELECT * FROM languages_routing WHERE ' . $condition);
			$request->execute([$tableName, $id, $originLanguage]);
			$columns = $request->fetchAll(\PDO::FETCH_ASSOC);

			if (empty($columns)) {
				if ($originLanguageTemp === false AND $originLanguage !== Site::parameter('default_language') AND $errorRecovery)
					return $this->getDB($tableName, $id, $columnsName, Site::parameter('default_language'));
				else
					return false;
			}
			elseif (count($columns) > 1) {
				$newColumns = [];
				foreach ($columns as $columnsElem)
					$newColumns[$columnsElem['column_name']] = $columnsElem['value'];
				return $newColumns;
			}
			else
				return $columns[0]['value'];
		}

		static function getLanguages($condition = '0 = 0', $originLanguage = false, $codesOnly = false) {
			global $db;

			$request = $db->query('SELECT code FROM languages WHERE ' . $condition . ' ORDER BY id');
			$languagesCodes = $request->fetchAll(\PDO::FETCH_ASSOC);

			if ($codesOnly)
				return $languagesCodes;
			else {
				$languages = [];
				foreach ($languagesCodes as $language)
					$languages[] = (new Languages($language['code'], false))->getLanguage($originLanguage);
				return $languages;
			}
		}
	}