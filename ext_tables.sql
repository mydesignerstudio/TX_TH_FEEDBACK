#
# Adding table
#
CREATE TABLE  `tx_th_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typo_page_id` int(11) NOT NULL,
  `typo_page_title` varchar(255) NOT NULL,
  `helpful` varchar(10) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `sys_language_uid` int(11) NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  `lastmod` int(255) NOT NULL,
  `received` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;