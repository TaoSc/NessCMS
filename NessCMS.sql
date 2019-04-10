SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `ip` varchar(39) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `post_id` int(11) NOT NULL,
  `post_type` varchar(255) NOT NULL,
  `hidden` int(1) NOT NULL DEFAULT 0,
  `content` text NOT NULL,
  `language` varchar(5) NOT NULL,
  `post_date` datetime NOT NULL,
  `modif_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `languages` (`id`, `code`, `enabled`) VALUES
(2, 'en-us', 1),
(1, 'fr-fr', 1);

CREATE TABLE `languages_routing` (
  `id` varchar(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `incoming_id` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `languages_routing` (`id`, `language`, `incoming_id`, `table_name`, `column_name`, `value`) VALUES
('3nvgtz5yyf_', 'fr-fr', 2, 'languages', 'lang_name', 'Anglais'),
('5SvXinUELrL', 'fr-fr', 3, 'members_types', 'name', 'Bannis'),
('7pM4JVpqPs_', 'fr-fr', 2, 'members_types', 'name', 'Membres'),
('7pM4uVpqPs7', 'en-us', 3, 'members_types', 'name', 'Banned'),
('7pM4uVpqPs_', 'fr-fr', 3, 'members_types', 'slug', 'bannis'),
('7pM8JVpqPs_', 'fr-fr', 3, 'members_types', 'name', 'Bannis'),
('7pMruVpqPs_', 'en-us', 3, 'members_types', 'slug', 'banned'),
('AMGgn3_xXFY', 'en-us', 1, 'members_types', 'slug', 'administrators'),
('D-Pc9fvevsU', 'en-us', 1, 'members_types', 'name', 'Administrators'),
('Ilgk6l-4Qqk', 'fr-fr', 1, 'members_types', 'slug', 'administrateurs'),
('jNvgtW5yyf_', 'en-us', 2, 'languages', 'lang_name', 'English'),
('llt87GZpZpd', 'en-us', 1, 'languages', 'country_name', 'France'),
('llt8gtZpZpd', 'fr-fr', 1, 'pages', 'index_text', '<p>Bonjour tout le monde.<br>Bienvenue sur mon site !</p>'),
('llt8xGdpZpW', 'fr-fr', 2, 'languages', 'country_name', 'États-Unis'),
('llt8xGopZpW', 'en-us', 2, 'languages', 'country_name', 'U.S.'),
('llt8xGZpZpd', 'en-us', 1, 'languages', 'lang_name', 'French'),
('llt8xGZpZpW', 'en-us', 2, 'members_types', 'slug', 'members'),
('llt8xrZpZpi', 'fr-fr', 1, 'languages', 'country_name', 'France'),
('lltreGZpZpd', 'en-us', 1, 'pages', 'index_text', '<p>Hello everyone.<br>Welcome to my website!</p>'),
('lZbBuL58diK', 'fr-fr', 1, 'tags', 'slug', 'defaut'),
('lZbBuL58dsK', 'en-us', 1, 'tags', 'slug', 'default'),
('lZoBlL78usK', 'fr-fr', 1, 'tags', 'name', 'Défaut'),
('lZoulL78usK', 'en-us', 1, 'tags', 'name', 'Default'),
('olt8xGZpZpd', 'fr-fr', 1, 'languages', 'lang_name', 'Français'),
('OywNmf76HeW', 'fr-fr', 1, 'members_types', 'name', 'Administrateurs'),
('RCr4vOChAKa', 'fr-fr', 2, 'members_types', 'slug', 'membres'),
('yVadvSuXQNm', 'en-us', 2, 'members_types', 'name', 'Members');

CREATE TABLE `medias` (
  `id` varchar(11) NOT NULL,
  `ext` varchar(4) NOT NULL,
  `author_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sizes` varchar(255) DEFAULT NULL,
  `post_date` datetime NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `avatar` varchar(4) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `registration` datetime NOT NULL,
  `birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `members` (`id`, `type_id`, `nickname`, `slug`, `avatar`, `email`, `password`, `first_name`, `last_name`, `registration`, `birth`) VALUES
(0, 3, 'Guest', 'guest', NULL, '', '', NULL, NULL, '2011-11-11 00:00:00', NULL);

CREATE TABLE `members_types` (
  `id` int(11) NOT NULL,
  `rights` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `members_types` (`id`, `rights`) VALUES
(1, '{\"admin_access\":true,\"config_edit\":true,\"news_create\":true,\"news_publish\":true,\"news_edit\":true,\"comment_edit\":true,\"comment_moderate\":true,\"poll_create\":true}'),
(2, '{\"admin_access\":0,\"config_edit\":0,\"news_create\":true,\"news_publish\":0,\"news_edit\":0,\"comment_edit\":true,\"comment_moderate\":0,\"poll_create\":0}'),
(3, '{\"admin_access\":0,\"config_edit\":0,\"news_create\":0,\"news_publish\":0,\"news_edit\":0,\"comment_edit\":0,\"comment_moderate\":0,\"poll_create\":0}');

CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `answers` varchar(255) NOT NULL,
  `poll_date` datetime NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `polls_users` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `type` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `img` varchar(11) NOT NULL,
  `authors_ids` varchar(255) NOT NULL,
  `priority` varchar(50) NOT NULL DEFAULT 'normal',
  `post_date` datetime NOT NULL,
  `comments` tinyint(1) NOT NULL DEFAULT 1,
  `votes` tinyint(1) NOT NULL DEFAULT 1,
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `site` (
  `name` char(100) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `site` (`name`, `value`) VALUES
('anonymous_coms', '1'),
('anonymous_votes', '1'),
('cache_enabled', '0'),
('coms_per_page', '10'),
('default_language', 'en-us'),
('default_user_type', '2'),
('directory', ?),
('name', ?),
('private_emails', '1'),
('theme', 'default'),
('url_rewriting', '0');

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'tag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tags` (`id`, `author_id`, `type`) VALUES
(1, 1, 'category');

CREATE TABLE `tags_relation` (
  `id` varchar(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `incoming_id` int(11) NOT NULL,
  `incoming_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `author_id` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `post_id` int(11) NOT NULL,
  `vote_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `languages`
  ADD PRIMARY KEY (`code`),
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `languages_routing`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `medias`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `members_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `polls_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `site`
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tags_relation`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `members_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `polls_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
