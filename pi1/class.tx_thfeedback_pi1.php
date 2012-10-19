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
	
	
	    #get page id
        $this->page_id     = $GLOBALS['TSFE']->id;
        $this->page_title  = $GLOBALS['TSFE']->page['title'];
	
		# language translations
		$message_thanks    = $this->pi_getLL('message_thanks');
		$message_missing   = $this->pi_getLL('message_missing');
		$feedback_label    = $this->pi_getLL('feedback_label');
		$feedback_text     = $this->pi_getLL('feedback_text');
		$btn_yes           = $this->pi_getLL('btn_yes');
		$btn_no            = $this->pi_getLL('btn_no');
		$btn_yesbut        = $this->pi_getLL('btn_yesbut');
		$btn_go            = $this->pi_getLL('btn_go');
		$input_note        = $this->pi_getLL('input_note');
		$message_duplicate = $this->pi_getLL('message_duplicate');

		# js config
		$animate_form      = 'yes';// yes / no
		$page_id           = $this->page_id;
		$page_title        = $this->page_title;
		
		# content start
		$content  = '';
		$content .= '<script language="Javascript">';		
		if($animate_form == 'no') {
			$content .= "var animate_form = 'no';";
			$feedback_label_class = 'feedback_label_hidden';
			$feedback_form_class = 'feedback_form_visible';
		}
		else {
			$content .= "var animate_form = 'yes';";
			$feedback_label_class = 'feedback_label_visible';
			$feedback_form_class = 'feedback_form_hidden';
		}
		$content .= "var message_thanks = '".$message_thanks."';";
		$content .= "var message_missing = '".$message_missing."';";
		$content .= "var page_id = '".$page_id."';";
		$content .= "var page_title = '".$page_title."';";
		$content .= "var message_duplicate = '".$message_duplicate."';";
		$content .= '</script>';
				
		$content .= '<hr />';
		$content .= '<div class="form_area feedback_area_off">';
		$content .= '<form id="formId"> ';
		$content .= '<div id="feedback_form" class="'.$feedback_form_class.'">';
		$content .= '<span class="initial_text">'.$feedback_text.'</span>';
		$content .= '<input type="button" value="'.$btn_yes.'" class="btn" id="btn_yes" />';
		$content .= '<input type="button" value="'.$btn_no.'" class="btn" id="btn_no" />';
		$content .= '<input type="button" value="'.$btn_yesbut.'" class="btn" id="btn_yesbut" />';
		$content .= '<span id="comment" class="hidden"><input type="text" value="'.$input_note.'" id="textfield" name="textfield" />';
		$content .= '<input type="button" value="'.$btn_go.'" class="btn" id="btn_go" /></span>';
		$content .= '</div><!-- #feedback_form -->';
		$content .= '<div id="feedback_label" class="'.$feedback_label_class.'">'.$feedback_label.'<input type="button" class="hidden_button" /></div>';
		$content .= '</form>';
		$content .= '</div><!-- #form_area -->';
		$content .= '<br /><br />';


		return $this->pi_wrapInBaseClass($content);
		
		# end :: content
		

		
	}
}

#t3lib_div::debug($GLOBALS['TSFE']->config,'AKIN WILL DIE PAGE CONFIG SEHEN :)');
# echo 'title: '.$GLOBALS['TSFE']->page['title'];
#ausgabe des gesamten oder teile von $GLOBALS['TSFE']

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/th_feedback/pi1/class.tx_thfeedback_pi1.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/th_feedback/pi1/class.tx_thfeedback_pi1.php']);
}

?>