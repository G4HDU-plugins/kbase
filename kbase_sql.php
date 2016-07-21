CREATE TABLE kbase (
  kbase_id int(10) unsigned NOT NULL auto_increment,
  kbase_parent int(10) unsigned NOT NULL default '0',
  kbase_question mediumtext NOT NULL,
  kbase_answer longtext NOT NULL,
  kbase_comment tinyint(1) unsigned NOT NULL default '0',
  kbase_datestamp int(10) unsigned NOT NULL default '0',
  kbase_author varchar(100) default '',
  kbase_order int(6) unsigned NOT NULL default '0',
  kbase_approved tinyint(3) unsigned NOT NULL default '0',
  kbase_views int(10) unsigned NOT NULL default '0',
  kbase_viewer TEXT,
  kbase_unique int(10) unsigned NOT NULL default '0',
  kbase_updated int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (kbase_id),
  KEY kbase_parent (kbase_parent)
) engine=innodb;
CREATE TABLE kbase_info (
  kbase_info_id int(10) unsigned NOT NULL auto_increment,
  kbase_info_title text NOT NULL,
  kbase_info_about text NOT NULL,
  kbase_info_parent int(10) unsigned default '0',
  kbase_info_class int(3) unsigned default '0',
  kbase_info_order tinyint(3) unsigned NOT NULL default '0',
  kbase_info_icon varchar(200) default '',
  PRIMARY KEY  (kbase_info_id),
  KEY kbase_info_parent (kbase_info_parent)
) engine=innodb;

