<?php
	namespace News;

	class Handling {
		static function getNews($condition = '0 = 0', $visible = true, $languageCheck = true, $offsetLimit = false, $order = 'DESC') {
			global $db;
			if ($offsetLimit)
				$offsetLimit = ' LIMIT ' . $offsetLimit;

			$request = $db->query('SELECT id FROM posts WHERE ' . $condition . ' ORDER BY id ' . $order . $offsetLimit);
			$newsIds = $request->fetchAll(\PDO::FETCH_ASSOC);

			$news = [];
			foreach ($newsIds as $newsLoop)
				$news[] = (new Single($newsLoop['id'], $visible, $languageCheck))->getNews();
			return array_filter($news);
		}
	}