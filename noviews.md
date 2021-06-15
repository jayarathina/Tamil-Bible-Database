# If Server / Hosting Provider does not allow you to create views:

Please note that if your server does not allow you to create views, then you can create a table based on the view and use it instead.

## How to proceed?

Step 1: create a table with following structure:

```MySQL
CREATE TABLE `mybibleview_dyn` (
  `verse_id` int(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 00000000,
  `txt` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

Step 2: Fill up the table using the following query:

```MySQL
INSERT INTO `mybibleview_dyn` ( (select `t_verses`.`id` AS `verse_id`,`t_verses`.`verse` AS `txt`,'V' AS `type` from `t_verses`) union (select `t_verseheaders`.`id` AS `verse_id`,`t_verseheaders`.`title` AS `txt`,'T' AS `type` from `t_verseheaders`) order by `verse_id`,`type` );
```

## Note:

In this case, tables like `t_verses` and `t_verseheaders` will not reflect in the front end. To do so, you have to truncate `mybibleview_dyn` and repopulate it:

```MySQL
truncate `mybibleview_dyn`;
INSERT INTO `mybibleview_dyn` ( (select `t_verses`.`id` AS `verse_id`,`t_verses`.`verse` AS `txt`,'V' AS `type` from `t_verses`) union (select `t_verseheaders`.`id` AS `verse_id`,`t_verseheaders`.`title` AS `txt`,'T' AS `type` from `t_verseheaders`) order by `verse_id`,`type` );
```

