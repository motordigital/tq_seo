<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Markus Blaschke (TEQneers GmbH & Co. KG) <blaschke@teqneers.de>
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
 * Tools
 *
 * @author		Blaschke, Markus <blaschke@teqneers.de>
 * @package 	tq_seo
 * @subpackage	lib
 * @version		$Id: class.tools.php 49810 2011-07-14 14:24:09Z mblaschke $
 */
class tx_tqseo_tools {
	
	###########################################################################
	# Attributes
	###########################################################################

	/**
	 * Page Select
	 * 
	 * @var t3lib_pageSelect
	 */
	protected static $sysPageObj = null;


	###########################################################################
	# Public methods
	###########################################################################


	/**
	 * Get current root pid
	 *
	 * @param	integer	$uid	Page UID
	 * @return	integer
	 */
	public static function getRootPid($uid = null) {
		global $TSFE;
		static $cache = array();
		$ret = null;
		
		if( $uid === null ) {
			$ret = (int)$TSFE->rootLine[0]['uid'];
		} else {
			
			if( !isset($cache[$uid]) ) {
				$cache[$uid] = null;
				$rootline = self::getRootLine($uid);
				
				if( !empty($rootline[0]) ) {
					$cache[$uid] = $rootline[0]['uid'];
				}
			}
			
			$ret = $cache[$uid];
		}
		
		return $ret;
	}
	
	/**
	 * Get current root pid
	 *
	 * @param	integer	$uid	Page UID
	 * @return	integer
	 */
	public static function getRootLine($uid = null) {
		$ret = array();
		
		if( $uid === null ) {
			$ret = (int)$TSFE->rootLine;
		} else {
			$ret = self::_getSysPageObj()->getRootLine($uid);
		}
		
		return $ret;
	}
	

	/**
	 * Get domain
	 *
	 * @return	array
	 */
	public static function getSysDomain() {
		global $TSFE, $TYPO3_DB;
		static $ret = null;

		if( $ret !== null ) {
			return $ret;
		}

		$ret = array();

		$host		= t3lib_div::getIndpEnv('HTTP_HOST');
		$rootPid	= self::getRootPid();

		$res = $TYPO3_DB->exec_SELECTquery(
			'*',
			'sys_domain',
			'pid = '.(int)$rootPid.' AND domainName = '.$TYPO3_DB->fullQuoteStr($host, 'sys_domain'). ' AND hidden = 0'
		);

		if( $row = $TYPO3_DB->sql_fetch_assoc($res) ) {
			$ret = $row;
		}

		return $ret;
	}
	
	/**
	 * Get extension configuration
	 *
	 * @param	string	$name		Name of config
	 * @param	boolean	$default	Default value
	 * @return	mixed
	 */
	public static function getExtConf($name, $default = null) {
		global $TYPO3_CONF_VARS;
		static $conf = null;
		$ret = $default;
		
		if( $conf === null ) {
		// Load ext conf
			$conf= unserialize($TYPO3_CONF_VARS['EXT']['extConf']['tq_seo']);
			if(!is_array($conf)) {
				$conf = array();
			}
		}
		
		if( isset($conf[$name]) ) {
			$ret = $conf[$name];
		}
		
		
		return $ret;
	}
	
	/**
	 * Call hook
	 *
	 * @param	string	$name		Name of hook
	 * @param	boolean	$obj		Object
	 * @param	boolean	$args		Args
	 * @return	mixed
	 */
	public static function callHook($name, $obj, &$args) {
		static $hookConf = null;
		
		// Fetch hooks config for tq_seo, minimize array lookups
		if( $hookConf === null ) {
			$hookConf = array();
			if( 	isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tq_seo']['hooks']) 
				&&	is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tq_seo']['hooks']) ) {
				$hookConf = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tq_seo']['hooks'];
			}
		}
		
		// Call hooks
		if( !empty($hookConf[$name]) && is_array($hookConf[$name]) ) {
			foreach($hookConf[$name] as $_funcRef) {
				if ($_funcRef) {
					t3lib_div::callUserFunction($_funcRef, $args, $obj);
				}
			}
		}
	}
	
	###########################################################################
	# Protected methods
	###########################################################################

	/**
	 * Get sys page object
	 * 
	 * @return	t3lib_pageSelect
	 */
	protected static function _getSysPageObj() {
		if(self::$sysPageObj === null) {
			self::$sysPageObj = t3lib_div::makeInstance('t3lib_pageSelect');
		}
		return self::$sysPageObj;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tq_seo/lib/class.tools.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tq_seo/lib/class.tools.php']);
}
?>