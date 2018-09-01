
-- --------------------------------------------------------

--
-- Structure for view `mybibleview_dyn`
--
DROP VIEW IF EXISTS `mybibleview_dyn`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `mybibleview_dyn`  AS  (select `t_verses`.`id` AS `verse_id`,`t_verses`.`verse` AS `txt`,'V' AS `type` from `t_verses`) union (select `t_verseheaders`.`id` AS `verse_id`,`t_verseheaders`.`title` AS `txt`,'T' AS `type` from `t_verseheaders`) order by `verse_id`,`type` ;
