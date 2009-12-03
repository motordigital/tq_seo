<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$tempColumns = array (
	'tx_tqseo_pagetitle' => array (
		'label' => 'LLL:EXT:tq_seo/locallang_db.xml:pages.tx_tqseo_pagetitle',
		'exclude' => 1,
		'config' => array (
			'type' => 'input',
			'size' => '30',
			'max' => '255',
			'checkbox' => '',
			'eval' => 'trim',
		)
	),

	'tx_tqseo_pagetitle_prefix' => array (
		'label' => 'LLL:EXT:tq_seo/locallang_db.xml:pages.tx_tqseo_pagetitle_prefix',
		'exclude' => 1,
		'config' => array (
			'type' => 'input',
			'size' => '30',
			'max' => '50',
			'checkbox' => '',
			'eval' => 'trim',
		)
	),

	'tx_tqseo_pagetitle_suffix' => array (
		'label' => 'LLL:EXT:tq_seo/locallang_db.xml:pages.tx_tqseo_pagetitle_suffix',
		'exclude' => 1,
		'config' => array (
			'type' => 'input',
			'size' => '30',
			'max' => '50',
			'checkbox' => '',
			'eval' => 'trim',
		)
	),

	'tx_tqseo_inheritance' => array(
		'exclude' => 1,
		'label'   => 'LLL:EXT:tq_seo/locallang_db.php:pages.tx_tqseo_inheritance',
		'config'  => array(
			'type'          => 'select',
			'items'         => array(
				array(
					'LLL:EXT:tq_seo/locallang_db.php:pages.tx_tqseo_inheritance.I.0',
					0
				),
				array(
					'LLL:EXT:tq_seo/locallang_db.php:pages.tx_tqseo_inheritance.I.1',
					1
				),
			),
			'size'          => 1,
			'maxitems'      => 1
		)
	),

	'tx_tqseo_is_exclude' => array (
		'label' => 'LLL:EXT:tq_seo/locallang_db.xml:pages.tx_tqseo_is_exclude',
		'exclude' => 1,
		'config' => array (
			'type' => 'check'
		)
	),

	'tx_tqseo_canonicalurl' => array (
		'label' => 'LLL:EXT:tq_seo/locallang_db.xml:pages.tx_tqseo_canonicalurl',
		'exclude' => 1,
		'config' => array (
			'type' => 'input',
			'size' => '30',
			'max' => '50',
			'checkbox' => '',
			'eval' => 'trim',
			'wizards' => Array(
				'_PADDING' => 2,
				'link' => Array(
					'type' => 'popup',
					'title' => 'Link',
					'icon' => 'link_popup.gif',
					'script' => 'browse_links.php?mode=wizard&act=url',
					'params' => array(
						'blindLinkOptions' => 'mail',
					),
					'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
				),
			),
		)
	),

);


t3lib_div::loadTCA('pages');
t3lib_extMgm::addTCAcolumns('pages',$tempColumns,1);
$GLOBALS['TCA']['pages']['types']['1']['showitem'] .= ', --div--;LLL:EXT:tq_seo/locallang_tca.xml:pages.tabs.seo, tx_tqseo_pagetitle, tx_tqseo_pagetitle_prefix, tx_tqseo_pagetitle_suffix, tx_tqseo_inheritance, tx_tqseo_is_exclude, tx_tqseo_canonicalurl';
$GLOBALS['TCA']['pages']['types']['4']['showitem'] .= ', --div--;LLL:EXT:tq_seo/locallang_tca.xml:pages.tabs.seo, tx_tqseo_pagetitle, tx_tqseo_pagetitle_prefix, tx_tqseo_pagetitle_suffix, tx_tqseo_inheritance, tx_tqseo_is_exclude, tx_tqseo_canonicalurl';

$tempColumns = array (
	'tx_tqseo_pagetitle' => array (
		'exclude' => 1,
		'label' => 'LLL:EXT:tq_seo/locallang_db.xml:pages_language_overlay.tx_tqseo_pagetitle',
		'config' => array (
			'type' => 'input',
			'size' => '30',
			'max' => '255',
			'checkbox' => '',
			'eval' => 'trim',
		)
	),
	'tx_tqseo_pagetitle_prefix' => array (
		'label' => 'LLL:EXT:tq_seo/locallang_db.xml:pages.tx_tqseo_pagetitle_prefix',
		'exclude' => 1,
		'config' => array (
			'type' => 'input',
			'size' => '30',
			'max' => '50',
			'checkbox' => '',
			'eval' => 'trim',
		)
	),
	'tx_tqseo_pagetitle_suffix' => array (
		'label' => 'LLL:EXT:tq_seo/locallang_db.xml:pages.tx_tqseo_pagetitle_suffix',
		'exclude' => 1,
		'config' => array (
			'type' => 'input',
			'size' => '30',
			'max' => '50',
			'checkbox' => '',
			'eval' => 'trim',
		)
	),

	'tx_tqseo_canonicalurl' => array (
		'label' => 'LLL:EXT:tq_seo/locallang_db.xml:pages.tx_tqseo_canonicalurl',
		'exclude' => 1,
		'config' => array (
			'type' => 'input',
			'size' => '30',
			'max' => '50',
			'checkbox' => '',
			'eval' => 'trim',
			'wizards' => Array(
				'_PADDING' => 2,
				'link' => Array(
					'type' => 'popup',
					'title' => 'Link',
					'icon' => 'link_popup.gif',
					'script' => 'browse_links.php?mode=wizard&act=url',
					'params' => array(
						'blindLinkOptions' => 'mail',
					),
					'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
				),
			),
		)
	),
);


t3lib_div::loadTCA('pages_language_overlay');
t3lib_extMgm::addTCAcolumns('pages_language_overlay',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('pages_language_overlay', 'tx_tqseo_pagetitle', '', 'after:nav_title');
t3lib_extMgm::addToAllTCAtypes('pages_language_overlay', 'tx_tqseo_pagetitle_prefix', '', 'after:tx_tqseo_pagetitle');
t3lib_extMgm::addToAllTCAtypes('pages_language_overlay', 'tx_tqseo_pagetitle_suffix', '', 'after:tx_tqseo_pagetitle_prefix');
t3lib_extMgm::addToAllTCAtypes('pages_language_overlay', 'tx_tqseo_canonicalurl', '', 'after:tx_tqseo_pagetitle_suffix');


t3lib_extMgm::addStaticFile($_EXTKEY,'static/default/', 'TEQneers SEO');

?>