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

require_once dirname(__FILE__).'/class.sitemap_base.php';

/**
 * Sitemap XML
 *
 * @author		Blaschke, Markus <blaschke@teqneers.de>
 * @package 	tq_seo
 * @subpackage	lib
 * @version		$Id$
 */
class tx_tqseo_sitemap_xml extends tx_tqseo_sitemap_base {

	/**
	 * Create Sitemap
	 * (either Index or page)
	 *
	 * @return string 		XML Sitemap
	 */
	protected function createSitemap() {
		$ret = '';
		$page = t3lib_div::_GP('page');

		$pageLimit		= $this->getExtConf('sitemap_pageSitemapItemLimit', '10000');
		$pageItems		= count($this->sitemapPages);
		$pageItemBegin	= $pageLimit * ($page-1);
		$pageCount		= ceil($pageItems/$pageLimit);


		if(empty($page) || $page == 'index') {
			$ret = $this->createSitemapIndex($pageCount);
		} elseif(is_numeric($page)) {
			if( $pageItemBegin <= $pageItems) {
				$this->sitemapPages = array_slice($this->sitemapPages, $pageItemBegin, $pageLimit);
				$ret = $this->createSitemapPage( $page );
			}
		}

		return $ret;
	}

	/**
	 * Create Sitemap Index
	 *
	 * @return string 		XML Sitemap
	 */
	protected function createSitemapIndex($pageCount) {
		global $TSFE;

		$sitemaps = array();

		// TODO: pages?
		$linkConf = array(
			'parameter'			=> $TSFE->id,
			'additionalParams'	=> '',
			'useCacheHash'		=> 1,
		);

		for($i=0; $i < $pageCount; $i++) {
			$linkConf['additionalParams'] = '&type='.$TSFE->type.'&page='.$pageCount;
			$sitemaps[] = t3lib_div::locationHeaderUrl($TSFE->cObj->typoLink_URL($linkConf));
		}

		$ret = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';


		foreach($sitemaps as $sitemapPage) {
			$ret .= '<sitemap><loc>'.htmlspecialchars($sitemapPage).'</loc></sitemap>';
		}

		$ret .= '</sitemapindex>';

		return $ret;
	}


	/**
	 * Create Sitemap Page
	 *
	 * @return string 		XML Sitemap
	 */
	protected function createSitemapPage() {
		$ret = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		$pagePriorityDefaultValue		= floatval($this->getExtConf('sitemap_pagePriorityDefaultValue', 1));
		$pagePriorityDepthMultiplier	= floatval($this->getExtConf('sitemap_pagePriorityDepthMultiplier', 1));
		$pagePriorityDepthModificator	= floatval($this->getExtConf('sitemap_pagePriorityDepthModificator', 1));

		foreach($this->sitemapPages as $sitemapPage) {
			if(empty($this->pages[ $sitemapPage['page_uid'] ])) {
				// invalid page
				continue;
			}

			$page = $this->pages[ $sitemapPage['page_uid'] ];

			#####################################
			# Page priority
			#####################################
			$pageDepth = $sitemapPage['page_depth'];
			$pageDepthBase = 1;

			if(!empty($sitemapPage['page_hash'])) {
				// page has module-content - trade as subpage
				++$pageDepth;
			}

			$pageDepth -= $pagePriorityDepthModificator;


			if($pageDepth > 0.1) {
				$pageDepthBase = 1/$pageDepth;
			}

			$pagePriority = $pagePriorityDefaultValue * ( $pageDepthBase * $pagePriorityDepthMultiplier );
			if(!empty($page['tx_tqseo_priority'])) {
				$pagePriority = $page['tx_tqseo_priority'] / 100;
			}

			$pagePriority = number_format($pagePriority, 2);

			if($pagePriority > 1) {
				$pagePriority = '1.00';
			} elseif($pagePriority <= 0) {
				$pagePriority = '0.00';
			}

			#####################################
			# Page informations
			#####################################

			// page Url
			$pageUrl = t3lib_div::locationHeaderUrl( $sitemapPage['page_url'] );

			// Page modification date
			$pageModifictionDate = date('c', $sitemapPage['tstamp']);

			// Page change frequency
			$pageChangeFrequency = NULL;
			if( !empty($page['tx_tqseo_change_frequency']) ) {
				$pageChangeFrequency = (int)$page['tx_tqseo_change_frequency'];
			} elseif( !empty($sitemapPage['page_change_frequency']) ) {
				$pageChangeFrequency = (int)$sitemapPage['page_change_frequency'];
			}

			if( !empty($pageChangeFrequency) && !empty( $this->pageChangeFrequency[$pageChangeFrequency] ) ) {
				$pageChangeFrequency = $this->pageChangeFrequency[$pageChangeFrequency];
			} else {
				$pageChangeFrequency = NULL;
			}


			#####################################
			# Sitemal page output
			#####################################
			$ret .= '<url>';
			$ret .= '<loc>'.htmlspecialchars($pageUrl).'</loc>';
			$ret .= '<lastmod>'.$pageModifictionDate.'</lastmod>';
			$ret .= '<priority>'.$pagePriority.'</priority>';

			if( !empty($pageChangeFrequency) ) {
				$ret .= '<changefreq>'.htmlspecialchars($pageChangeFrequency).'</changefreq>';
			}

			$ret .= '</url>';
		}


		$ret .= '</urlset>';

		return $ret;
	}

}

?>