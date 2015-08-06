SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `comments` (
`id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `ip` varchar(39) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL,
  `post_type` varchar(255) NOT NULL,
  `hidden` int(1) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `language` varchar(5) NOT NULL,
  `post_date` datetime NOT NULL,
  `modif_date` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `languages` (
`id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=3;

INSERT INTO `languages` (`id`, `code`, `enabled`) VALUES
(1, 'fr-fr', 1),
(2, 'en-us', 1);

CREATE TABLE IF NOT EXISTS `languages_routing` (
  `id` varchar(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `incoming_id` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `languages_routing` (`id`, `language`, `incoming_id`, `table_name`, `column_name`, `value`) VALUES
('3nvgtW5yyf_', 'fr-fr', 1, 'members_types', 'name', 'Administrateurs'),
('3nvgtz5yyf_', 'fr-fr', 2, 'languages', 'lang_name', 'Anglais'),
('7pM4JVpqPs_', 'fr-fr', 2, 'members_types', 'name', 'Membres'),
('7pM4uVpqPs7', 'en-us', 3, 'members_types', 'name', 'Banned'),
('7pM4uVpqPs_', 'fr-fr', 3, 'members_types', 'slug', 'bannis'),
('7pM8JVpqPs_', 'fr-fr', 3, 'members_types', 'name', 'Bannis'),
('7pMruVpqPs_', 'en-us', 3, 'members_types', 'slug', 'banned'),
('bLf3ckEe2-y', 'en-us', 1, 'members_types', 'name', 'Administrators'),
('BXBAreulkeC', 'en-us', 1, 'members_types', 'slug', 'admins'),
('jNvgtW5yyf_', 'en-us', 2, 'languages', 'lang_name', 'English'),
('llt87GZpZpd', 'en-us', 1, 'languages', 'country_name', 'France'),
('llt8xGdpZpW', 'fr-fr', 2, 'languages', 'country_name', 'États-Unis'),
('llt8xGopZpW', 'en-us', 2, 'languages', 'country_name', 'U.S.'),
('llt8xGZpZpd', 'en-us', 1, 'languages', 'lang_name', 'French'),
('llt8xGZpZpW', 'en-us', 2, 'members_types', 'slug', 'members'),
('llt8xrZpZpi', 'fr-fr', 1, 'languages', 'country_name', 'France'),
('lZbBlLTWdsK', 'fr-fr', 1, 'members_types', 'slug', 'admins'),
('lZbBuL58diK', 'fr-fr', 1, 'tags', 'slug', 'defaut'),
('lZbBuL58dsK', 'en-us', 1, 'tags', 'slug', 'default'),
('lZoBlL78usK', 'fr-fr', 1, 'tags', 'name', 'Défaut'),
('lZoulL78usK', 'en-us', 1, 'tags', 'name', 'Default'),
('olt8xGZpZpd', 'fr-fr', 1, 'languages', 'lang_name', 'Français'),
('RCr4vOChAKa', 'fr-fr', 2, 'members_types', 'slug', 'membres'),
('yVadvSuXQNm', 'en-us', 2, 'members_types', 'name', 'Members');

CREATE TABLE IF NOT EXISTS `medias` (
  `id` varchar(11) NOT NULL,
  `ext` varchar(4) NOT NULL,
  `author_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sizes` varchar(255) DEFAULT NULL,
  `post_date` datetime NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `members` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

INSERT INTO `members` (`id`, `type_id`, `nickname`, `slug`, `avatar`, `email`, `password`, `first_name`, `last_name`, `registration`, `birth`) VALUES
(0, 3, 'Guest', 'guest', NULL, '', '', NULL, NULL, '2011-11-11 00:00:00', NULL);

CREATE TABLE IF NOT EXISTS `members_types` (
  `id` int(11) NOT NULL,
  `rights` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=3;

INSERT INTO `members_types` (`id`, `rights`) VALUES
(1, '{"admin_access":1,"config_edit":1,"news_create":1,"news_publish":1,"news_edit":1,"comment_edit":1,"comment_moderate":1}'),
(2, '{"admin_access":0,"config_edit":0,"news_create":1,"news_publish":0,"news_edit":0,"comment_edit":1,"comment_moderate":0}'),
(3, '{"admin_access":0,"config_edit":0,"news_create":0,"news_publish":0,"news_edit":0,"comment_edit":0,"comment_moderate":0}');

CREATE TABLE IF NOT EXISTS `polls` (
`id` int(11) NOT NULL,
  `answers` varchar(255) NOT NULL,
  `poll_date` datetime NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `polls_users` (
`id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `posts` (
`id` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `img` varchar(11) NOT NULL,
  `authors_ids` varchar(255) NOT NULL,
  `priority` varchar(50) NOT NULL DEFAULT 'normal',
  `post_date` datetime NOT NULL,
  `comments` tinyint(1) NOT NULL DEFAULT '1',
  `votes` tinyint(1) NOT NULL DEFAULT '1',
  `views` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `site` (
  `name` char(100) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `site` (`name`, `value`) VALUES
('anonymous_coms', '1'),
('anonymous_votes', '1'),
('coms_per_page', '10'),
('default_language', 'en-us'),
('default_users_type', '2'),
('name', ?),
('directory', ?),
('private_emails', '1'),
('theme', 'default'),
('cache_enabled', '0'),
('url_rewriting', '0');

CREATE TABLE IF NOT EXISTS `tags` (
`id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'tag'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2;

INSERT INTO `tags` (`id`, `author_id`, `type`) VALUES
(1, 1, 'category');

CREATE TABLE IF NOT EXISTS `tags_relation` (
  `id` varchar(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `incoming_id` int(11) NOT NULL,
  `incoming_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `votes` (
`id` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `author_id` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `post_id` int(11) NOT NULL,
  `vote_date` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `languages`
 ADD PRIMARY KEY (`code`), ADD UNIQUE KEY `id` (`id`);

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `languages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `members`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `members_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `polls`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `polls_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `votes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;