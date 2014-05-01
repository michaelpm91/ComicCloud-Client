SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `comicNew` (
  `user_id` int(11) NOT NULL,
  `comic_id` varchar(255) NOT NULL,
  `comic_series_id` varchar(255) NOT NULL,
  `comic_series` varchar(255) NOT NULL,
  `comic_issue` varchar(255) NOT NULL,
  `comic_start_year` int(11) DEFAULT NULL,
  `comic_cover_image` varchar(255) NOT NULL,
  `pages` varchar(8000) NOT NULL,
  `finished_processing` tinyint(1) NOT NULL,
  `size` int(11) NOT NULL,
  UNIQUE KEY `comic_id` (`comic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
