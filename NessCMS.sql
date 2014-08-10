SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `categories` (
`id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `languages` (
`id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=3 ;

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
('3wHBAROiB7h', 'fr-fr', 4, 'polls_answers', 'name', 'Vote blanc.'),
('7pM4JVpqPs_', 'fr-fr', 2, 'members_types', 'name', 'Membres'),
('7pM4uVpqPs7', 'en-us', 3, 'members_types', 'name', 'Banned'),
('7pM4uVpqPs_', 'fr-fr', 3, 'members_types', 'slug', 'bannis'),
('7pM8JVpqPs_', 'fr-fr', 3, 'members_types', 'name', 'Bannis'),
('7pMruVpqPs_', 'en-us', 3, 'members_types', 'slug', 'banned'),
('9ca7JFjJxKh', 'fr-fr', 1, 'polls_answers', 'name', 'Ouais !'),
('bLf3ckEe2-y', 'en-us', 1, 'members_types', 'name', 'Administrators'),
('brIFSjB9zeZ', 'en-us', 2, 'polls_answers', 'name', 'No!'),
('BXBAreulkeC', 'en-us', 1, 'members_types', 'slug', 'admins'),
('dsQ_PnrblOA', 'fr-fr', 3, 'polls_answers', 'name', 'Pourquoi pas…'),
('FEzqW9pyUX3', 'fr-fr', 2, 'polls_answers', 'name', 'Non !'),
('itDvL4s5xnI', 'fr-fr', 1, 'polls_questions', 'name', 'Mario Kart 8 vous fera-t-il acheter une Wii U ?'),
('JM3trjwJeXA', 'en-us', 1, 'polls_questions', 'name', 'Will Mario Kart 8 make you buy a Wii U?'),
('jNvgtW5yyf_', 'en-us', 2, 'languages', 'lang_name', 'English'),
('llt87GZpZpd', 'en-us', 1, 'languages', 'country_name', 'France'),
('llt8xGdpZpW', 'fr-fr', 2, 'languages', 'country_name', 'États-Unis'),
('llt8xGopZpW', 'en-us', 2, 'languages', 'country_name', 'U.S.'),
('llt8xGZpZpd', 'en-us', 1, 'languages', 'lang_name', 'French'),
('llt8xGZpZpW', 'en-us', 2, 'members_types', 'slug', 'members'),
('llt8xrZpZpi', 'fr-fr', 1, 'languages', 'country_name', 'France'),
('lZbBlL78dsK', 'fr-fr', 1, 'posts', 'slug', 'mario-kart-8-mise-a-jour-en-vue'),
('lZbBlLT8dsK', 'fr-fr', 1, 'posts', 'availability', '1'),
('lZbBlLTWdsK', 'fr-fr', 1, 'members_types', 'slug', 'admins'),
('lZbilL78dsK', 'fr-fr', 1, 'posts', 'sub_title', 'Préparez-vous pour le 27 août'),
('lZoBlL78dsK', 'fr-fr', 1, 'posts', 'title', 'Mario Kart 8 : mise à jour en vue'),
('olt8xGZpZpd', 'fr-fr', 1, 'languages', 'lang_name', 'Français'),
('PworpHF25ny', 'en-us', 3, 'polls_answers', 'name', 'Why not…'),
('RCr4vOChAKa', 'fr-fr', 2, 'members_types', 'slug', 'membres'),
('XNyagfnuDhg', 'en-us', 1, 'polls_answers', 'name', 'Yeah!'),
('yVadvSuXQNm', 'en-us', 2, 'members_types', 'name', 'Members'),
('ZrL5IsX7yiu', 'en-us', 4, 'polls_answers', 'name', 'Blank vote.');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

INSERT INTO `members` (`id`, `type_id`, `nickname`, `slug`, `avatar`, `email`, `password`, `first_name`, `last_name`, `registration`, `birth`) VALUES
(0, 3, 'Guest', 'guest', NULL, '', '', NULL, NULL, '2011-11-11 00:00:00', NULL);

CREATE TABLE IF NOT EXISTS `members_types` (
  `id` int(11) NOT NULL,
  `rights` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `members_types` (`id`, `rights`) VALUES
(1, '{"admin_access":1}'),
(2, '{"admin_access":0}'),
(3, '{"admin_access":0}');

CREATE TABLE IF NOT EXISTS `polls` (
`id` int(11) NOT NULL,
  `answers` varchar(255) NOT NULL,
  `poll_date` datetime NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

INSERT INTO `polls` (`id`, `answers`, `poll_date`, `author_id`) VALUES
(1, '[{"id":1,"class":"success"},{"id":2,"class":"danger"},{"id":3,"class":"info"},{"id":4,"class":"primary"}]', '2014-08-02 23:45:33', 1);

CREATE TABLE IF NOT EXISTS `polls_users` (
`id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `posts` (
`id` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `img` varchar(11) NOT NULL,
  `authors_ids` varchar(255) NOT NULL,
  `priority` varchar(50) NOT NULL DEFAULT 'normal',
  `post_date` datetime NOT NULL,
  `modif_date` int(11) DEFAULT NULL,
  `views` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `site` (
  `name` char(100) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `site` (`name`, `value`) VALUES
('anonymous_coms', '0'),
('anonymous_votes', '0'),
('default_language', 'fr-fr'),
('directory', '/nesscms/'),
('name', 'Tao'),
('private_emails', '1'),
('url_rewriting', '1');

CREATE TABLE IF NOT EXISTS `votes` (
`id` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `author_id` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `post_id` int(11) NOT NULL,
  `vote_date` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;


ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `languages`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code` (`code`);

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

ALTER TABLE `votes`
 ADD PRIMARY KEY (`id`);


ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `languages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `members`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `polls`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `polls_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `votes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
