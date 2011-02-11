<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Markus Blaschke (TEQneers GmbH & Co. KG) <blaschke@teqneers.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Sitemap Base
 *
 * @author		Blaschke, Markus <blaschke@teqneers.de>
 * @package 	tq_seo
 * @subpackage	lib
 * @version		$Id$
 */
abstract class tx_tqseo_sitemap_base {
	###########################################################################
	# Attributes
	###########################################################################

	/**
	 * Current root pid
	 * @var integer
	 */
	protected $rootPid		= NULL;

	/**
	 * Sitemap pages
	 * @var array
	 */
	protected $sitemapPages	= array();

	/**
	 * Page lookups
	 * @var array
	 */
	protected $pages		= array();

	/**
	 * Extension configuration
	 * @var array
	 */
	protected $extConf		= array();

	/**
	 * Page change frequency definition list
	 * @var array
	 */
	protected $pageChangeFrequency = array(
		1 => 'always',
		2 => 'hourly',
		3 => 'daily',
		4 => 'weekly',
		5 => 'monthly',
		6 => 'yearly',
		7 => 'never',
	);

	###########################################################################
	# Methods
	###########################################################################

	/**
	 * Fetch sitemap information and generate sitemap
	 */
	public function main() {
		global $TSFE, $TYPO3_DB, $TYPO3_CONF_VARS;

		// INIT
		$this->rootPid = (int)$TSFE->rootLine[0]['uid'];

		// check if sitemap is enabled
		if( empty($TSFE->tmpl->setup['plugin.']['tq_seo.']['sitemap.']['enable']) ) {
			$this->showError();
		}

		// Load ext conf
		$this->extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['tq_seo']);
		if(!is_array($this->extConf)) {
			$this->extConf = array();
		}

		$typo3Pids = array();

		#########################################
		# Fetch sitemap pages
		#########################################
		$query = 'SELECT
						ts.*
					FROM
						tx_tqseo_sitemap ts
						INNER JOIN pages p
							ON		p.uid = ts.page_uid
								AND	p.deleted = 0
								AND p.hidden = 0
								AND p.tx_tqseo_is_exclude = 0
					WHERE
						ts.page_rootpid = '.$TYPO3_DB->fullQuoteStr($this->rootPid, 'tx_tqseo_sitemap');

		if( $this->getExtConf('sitemap_ObeySysLanguage', false) ) {
			$sysLanguageId = 0;
			if(!empty($TSFE->tmpl->setup['config.']['sys_language_uid'])) {
				$sysLanguageId = (int)$TSFE->tmpl->setup['config.']['sys_language_uid'];
			}

			$query .= ' AND ts.page_language = '.$TYPO3_DB->fullQuoteStr($sysLanguageId, 'tx_tqseo_sitemap');
		}

		$query .= ' ORDER BY
						ts.page_depth ASC,
						p.pid ASC,
						p.sorting ASC';

		$res = $TYPO3_DB->sql_query($query);

		if( !$res ) {
			$this->showError();
		}

		while( $row = $TYPO3_DB->sql_fetch_assoc($res) ) {
			$this->sitemapPages[] = $row;

			$sitemapPageId = $row['page_uid'];
			$typo3Pids[$sitemapPageId] = $sitemapPageId;
		}

		#########################################
		# Fetch pages
		#########################################
		if(!empty($typo3Pids)) {
			$query = 'SELECT
							*
						FROM
							pages
						WHERE
								uid IN ('.implode(',', $typo3Pids).')';
			$res = $TYPO3_DB->sql_query($query);

			if( !$res ) {
				$this->showError();
			}

			while( $row = $TYPO3_DB->sql_fetch_assoc($res) ) {
				$this->pages[ $row['uid'] ] = $row;
			}
		}

		$ret = $this->createSitemap();

		return $ret;
	}

	/**
	 * Get extension configuration (by name)
	 *
	 * @param	string	$name			Configuration settings name
	 * @param	mixed	$defaultValue	Default value (if configuration doesn't exists)
	 * @return	mixed
	 */
	protected function getExtConf($name, $defaultValue = NULL) {
		$ret = $defaultValue;
		if(!empty($this->extConf[$name])) {
			$ret = $this->extConf[$name];
		}

		return $ret;
	}

	/**
	 * Show error
	 *
	 * @param	string	$msg			Message
	 */
	protected function showError($msg = null) {
		global $TSFE;

		if( $msg === null ) {
			$msg = 'Sitemap is not available, please check your configuration';
		}

		header('HTTP/1.0 503 Service Unavailable');
		echo $msg;
		exit;
	}

	abstract protected function createSitemap();
}

?>