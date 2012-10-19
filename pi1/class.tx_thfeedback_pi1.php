<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Akin Ajewole <info@typohosting.at>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

// require_once(PATH_tslib . 'class.tslib_pibase.php');

/**
 * Plugin 'TH Feedback' for the 'th_feedback' extension.
 *
 * @author	Akin Ajewole <info@typohosting.at>
 * @package	TYPO3
 * @subpackage	tx_thfeedback
 */
class tx_thfeedback_pi1 extends tslib_pibase {
	public $prefixId      = 'tx_thfeedback_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_thfeedback_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'th_feedback';	// The extension key.
	public $pi_checkCHash = TRUE;
	
	/**
	 * The main method of the Plugin.
	 *
	 * @param string $content The Plugin content
	 * @param array $conf The Plugin configuration
	 * @return string The content that is displayed on the website
	 */
	public function main($content, array $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
		# load external css / js in via additionalHeaderData()
		$GLOBALS ['TSFE']->additionalHeaderData [$this->extKey . '/css_1'] = '<link href="' . t3lib_extMgm::siteRelPath ( $this->extKey ) . 'pi1/css/main.css" rel="stylesheet" type="text/css" />';
		$GLOBALS ['TSFE']->additionalHeaderData [$this->extKey . '/css_2'] = '<link href="' . t3lib_extMgm::siteRelPath ( $this->extKey ) . 'pi1/css/ui_green.css" rel="stylesheet" type="text/css" />';
		$GLOBALS ['TSFE']->additionalHeaderData [$this->extKey . '/js_1'] = '<script type="text/javascript" src="' . t3lib_extMgm::siteRelPath ( $this->extKey ) . 'pi1/js/jquery.js"></script>';
		$GLOBALS ['TSFE']->additionalHeaderData [$this->extKey . '/js_2'] = '<script type="text/javascript" src="' . t3lib_extMgm::siteRelPath ( $this->extKey ) . 'pi1/js/jquery_custom_functions.js"></script>';
	
		# content
		$content = '';
		$content .= '<hr />';
		$content .= '<div class="form_area feedback_area_off">';
		$content .= '<form id="formId"> ';
		$content .= '<div id="feedback_form">';
		$content .= '<span class="initial_text">Was this post helpful?</span>';
		$content .= '<input type="button" value="Yes" class="btn" id="btn_yes" />';
		$content .= '<input type="button" value="No" class="btn" id="btn_no" />';
		$content .= '<input type="button" value="Yes, but.." class="btn" id="btn_yesbut" />';
		$content .= '<span id="comment" class="hidden"><input type="text" value="Your comment" id="textfield" name="textfield" />';
		$content .= '<input type="button" value="Go!" class="btn" id="btn_go" /></span>';
		$content .= '</div><!-- #feedback_form -->';
		$content .= '<div id="feedback_label" class="feedback_label">Feedback<input type="button" class="hidden_button" /></div>';
		$content .= '</form>';
		$content .= '</div><!-- #form_area --><!-- #feedback_area 2 -->';
		$content .= '<br /><br />';


		return $this->pi_wrapInBaseClass($content);
		
	}
}



if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/th_feedback/pi1/class.tx_thfeedback_pi1.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/th_feedback/pi1/class.tx_thfeedback_pi1.php']);
}

?>