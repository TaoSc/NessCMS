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
			$this->language = array_merge($this->language, $this->getDB('languages', $this->language['id'], '*', $originLanguage));
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

		function getDB2($tableName, $index, $columnsName = '*', $errorRecovery = true, $getId = false, $originLanguage = false) {
			global $db;
			if ($originLanguage === false)
				$originLanguage = $this->language['code'];
			if ($getId) {
				$from = 'value';
				$to = 'incoming_id';
			}
			else {
				$from = 'incoming_id';
				$to = 'value';
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
				if ($originLanguage !== Site::parameter('default_language') AND $errorRecovery)
					return $this->getDB($tableName, $index, $columnsName, Site::parameter('default_language'));
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

		static function getLanguages($condition = 'TRUE', $originLanguage = false, $codesOnly = false) {
			return Handling::getList($condition, 'languages', __CLASS__, 'Language', false, $codesOnly, false, 'code', $originLanguage, false);
		}
	}
