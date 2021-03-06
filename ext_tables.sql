#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_tqseo_pagetitle varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_pagetitle_prefix varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_pagetitle_suffix varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_is_exclude int(1) DEFAULT '0' NOT NULL,
	tx_tqseo_inheritance int(11) DEFAULT '0' NOT NULL
);



#
# Table structure for table 'pages_language_overlay'
#
CREATE TABLE pages_language_overlay (
	tx_tqseo_pagetitle varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_pagetitle_prefix varchar(255) DEFAULT '' NOT NULL,
	tx_tqseo_pagetitle_suffix varchar(255) DEFAULT '' NOT NULL,
);
