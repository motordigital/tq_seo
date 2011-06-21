#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_tqseo_pagetitle varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_pagetitle_prefix varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_pagetitle_suffix varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_is_exclude int(1) DEFAULT '0' NOT NULL,
	tx_tqseo_inheritance int(11) DEFAULT '0' NOT NULL,
	tx_tqseo_canonicalurl varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_priority int(11) DEFAULT '0' NOT NULL,
	tx_tqseo_change_frequency int(4) DEFAULT '0' NOT NULL
);

#
# Table structure for table 'pages_language_overlay'
#
CREATE TABLE pages_language_overlay (
	tx_tqseo_pagetitle varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_pagetitle_prefix varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_pagetitle_suffix varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_canonicalurl varchar(255) DEFAULT '' NOT NULL,
);

#
# Table structure for table 'tq_seo_cache'
#
CREATE TABLE tx_tqseo_cache (
	uid int(11) NOT NULL auto_increment,
	tstamp int(11) DEFAULT '0' NOT NULL,
	page_uid int(11) DEFAULT '0' NOT NULL,
	cache_section varchar(10) DEFAULT '' NOT NULL,
	cache_identifier varchar(10) DEFAULT '' NOT NULL,
	cache_content blob,
	PRIMARY KEY (uid),
	UNIQUE cache_key (page_uid,cache_section,cache_identifier),
	KEY cache_sect_id (cache_section,cache_identifier)
) ENGINE=InnoDB;

#
# Table structure for table 'tx_tqseo_settings'
#
#CREATE TABLE tx_tqseo_settings (
#	uid int(11) NOT NULL auto_increment,
#	pid int(11) DEFAULT '0' NOT NULL,
#	tstamp int(11) DEFAULT '0' NOT NULL,
#	crdate int(11) DEFAULT '0' NOT NULL,
#	cruser_id int(11) DEFAULT '0' NOT NULL,
#	robotstxt TEXT,
#	PRIMARY KEY (uid)
#) ENGINE=InnoDB;

