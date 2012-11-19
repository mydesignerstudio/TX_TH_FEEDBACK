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


$GLOBALS['LANG']->includeLLFile('EXT:th_feedback/mod1/locallang.xml');
//require_once(PATH_t3lib . 'class.t3lib_scbase.php');
$GLOBALS['BE_USER']->modAccess($MCONF, 1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]


/**
 * Module 'TH-Feedback' for the 'th_feedback' extension.
 *
 * @author	Akin Ajewole <info@typohosting.at>
 * @package	TYPO3
 * @subpackage	tx_thfeedback
 */
class tx_thfeedback_module1 extends t3lib_SCbase {
	protected $pageinfo;
	
	
	/**
	 * Initializes the module.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();

		/*
		if (t3lib_div::_GP('clear_all_cache'))	{
			$this->include_once[] = PATH_t3lib . 'class.t3lib_tcemain.php';
		}
		*/
	}

	/**
	 * Main function of the module. Write the content to $this->content
	 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
	 *
	 * @return void
	 */
	public function main() {
			// Access check!
			// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id, $this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;
	
		if (($this->id && $access) || ($GLOBALS['BE_USER']->user['admin'] && !$this->id)) {

				// Draw the header.
			$this->doc = t3lib_div::makeInstance('mediumDoc');
			$this->doc->backPath = $GLOBALS['BACK_PATH'];
			$this->doc->form = '<form action="" method="post" enctype="multipart/form-data">';

				// JavaScript
			$this->doc->JScode = '
				<script language="javascript" type="text/javascript">
					script_ended = 0;
					function jumpToUrl(URL)	{
						document.location = URL;
					}
				</script>
				
				
				<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
				<script type="text/javascript" src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
				<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" type="text/css" />
				
				<script type="text/javascript">
				
				function openComment (number) {
					$(document).ready(function() {
						var div = \'.div_\' + number;
						/** TOGGLE COMMENTS **/
						$(div).fadeToggle(300,\'linear\');
					}); // end of ".ready"
				}
				</script>
				
				<script>
				$(function() {
				  $( "#datepicker1" ).datepicker({dateFormat : "dd.mm.yy"});
				  $( "#datepicker2" ).datepicker({dateFormat : "dd.mm.yy"});
				});
				</script>
				
				<link href="http://fonts.googleapis.com/css?family=lato:900,400,300,100" rel="stylesheet" type="text/css">
				<link rel="stylesheet" href="../typo3conf/ext/th_feedback/mod1/be.css" type="text/css" />

				
			';
			$this->doc->postCode = '
				<script language="javascript" type="text/javascript">
					script_ended = 1;
					if (top.fsMod) top.fsMod.recentIds["web"] = 0;
				</script>
				
			';

			$headerSection = $this->doc->getHeader('pages', $this->pageinfo, $this->pageinfo['_thePath']) . '<br />'
				. $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.path') . ': ' . t3lib_div::fixed_lgd_cs($this->pageinfo['_thePath'], -50);

			$this->content .= $this->doc->startPage($GLOBALS['LANG']->getLL('title'));
			#$this->content .= $this->doc->header($GLOBALS['LANG']->getLL('title'));
			
			 

			
			#$this->content .= $this->doc->spacer(5);
			#$this->content .= $this->doc->section('',$this->doc->funcMenu($headerSection, t3lib_BEfunc::getFuncMenu($this->id, 'SET[function]', $this->MOD_SETTINGS['function'], $this->MOD_MENU['function'])));
			#$this->content .= $this->doc->divider(5);

				// Render content:
			$this->moduleContent();

				// Shortcut
			#if ($GLOBALS['BE_USER']->mayMakeShortcut()) {
			#	$this->content .= $this->doc->spacer(20) . $this->doc->section('', $this->doc->makeShortcutIcon('id', implode(',', array_keys($this->MOD_MENU)), $this->MCONF['name']));
			#}

			$this->content .= $this->doc->spacer(10);
		} else {
				// If no access or if ID == zero

			$this->doc = t3lib_div::makeInstance('mediumDoc');
			$this->doc->backPath = $GLOBALS['BACK_PATH'];

			$this->content .= $this->doc->startPage($GLOBALS['LANG']->getLL('title'));
			$this->content .= $this->doc->header($GLOBALS['LANG']->getLL('title'));
			$this->content .= $this->doc->spacer(5);
			$this->content .= $this->doc->spacer(10);
		}
	
	}

	/**
	 * Prints out the module HTML.
	 *
	 * @return void
	 */
	public function printContent() {
		$this->content .= $this->doc->endPage();
		echo $this->content;
	}

	/**
	 * Generates the module content.
	 *
	 * @return void
	 */
	protected function moduleContent() {
		// define database table
		$db_table  =  "tx_th_feedback";
		$db_pages  =  "pages";
		$db_pages_trans  =  "pages_language_overlay";
		$row_counter = '';
		
		// -------------------------------
		// language translations
		// -------------------------------
		#$title_yes                  =  parent::pi_getLL('title_yes');
		$LL_title_header             =  $GLOBALS['LANG']->getLL('title_header');
		$LL_title_top                =  $GLOBALS['LANG']->getLL('title_top');
		$LL_title_page_id            =  $GLOBALS['LANG']->getLL('title_page_id');
		$LL_title_page_title         =  $GLOBALS['LANG']->getLL('title_page_title');
		$LL_title_yes                =  $GLOBALS['LANG']->getLL('title_yes');
		$LL_title_no                 =  $GLOBALS['LANG']->getLL('title_no');
		$LL_title_yesbut             =  $GLOBALS['LANG']->getLL('title_yesbut');
		$LL_title_total              =  $GLOBALS['LANG']->getLL('title_total');
		$LL_title_comments           =  $GLOBALS['LANG']->getLL('title_comments');
		$LL_title_choosetimeframe    =  $GLOBALS['LANG']->getLL('title_choosetimeframe');
		$LL_title_poweredby          =  $GLOBALS['LANG']->getLL('title_poweredby');
		$title_selectdatetooltip     =  $GLOBALS['LANG']->getLL('title_selectdatetooltip');
		$LL_btn_viewcomments         =  $GLOBALS['LANG']->getLL('btn_viewcomments');
		$LL_btn_dateselect           =  $GLOBALS['LANG']->getLL('btn_dateselect');
		

		# $_GET
		$date_from = $_GET['datepicker_from'];
		$date_to   = $_GET['datepicker_to'];
		
		$sql_1         = "SELECT * FROM $db_table ORDER BY received LIMIT 0,1";
		$results_1     =  mysql_query($sql_1) OR die(mysql_error());
		while ($row_1  =  mysql_fetch_object($results_1)) { // get db data as array
			// collect db data
			$received         =  $row_1->received;   
		}
		$youngest_date    =  date("d.m.Y",$received);
		
		$timestamp  = time();
		$today_date = date("d.m.Y",$timestamp);

		$date_from_get = $date_from;
		$date_to_get   = $date_to;

		if(!isset($_GET['datepicker_from'])) $date_from_get = $youngest_date;
		if(!isset($_GET['datepicker_from'])) $date_to_get   = $today_date;

		if(isset($_GET['datepicker_from'])) {
			# $date_from
			$array_date_from = array();
			$array_date_from = explode(".", $date_from);
			$date_from = $array_date_from[2].'-'.$array_date_from[1].'-'.$array_date_from[0].' 00:00:00';
			
			# $date_to
			$array_date_to = array();
			$array_date_to = explode(".", $date_to);
			$date_to = $array_date_to[2].'-'.$array_date_to[1].'-'.$array_date_to[0].' 23:59:59';
		}

		echo '<div class="th_container">';
		echo '<img src="../typo3conf/ext/th_feedback/mod1/moduleicon.gif" /> <p class="th_header">'.$LL_title_header.'</p>'.'<br /><br />';
		echo '<form action="'.$_SERVER["PHP_SELF"].'" method="get"><strong>'.$LL_title_choosetimeframe.'</strong>';
		echo '<img src="../typo3conf/ext/th_feedback/mod1/icon_date.gif" title="'.$title_selectdatetooltip.'" /> <input type="text" name="datepicker_from" id="datepicker1" value="'.$date_from_get.'"> <img src="../typo3conf/ext/th_feedback/mod1/icon_date.gif" title="'.$title_selectdatetooltip.'" /> <input type="text" name="datepicker_to" id="datepicker2" value="'.$date_to_get.'"> <input type="hidden" name="M" value="user_txthfeedbackM1"> <input type="submit" value="'.$LL_btn_dateselect.'" name="submit" /> <br /><br /></form>';
		echo '</div>';

		
		#$content = 'Akin was here :)';
		#$this->content .= $content;

		####################################################  MYSQL QUERY > TEMPLATE FILLING
		/*
		// First we need to enable the query storage
		$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = true;

		$select = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		  '*', #select
		  'tx_th_feedback', #from
		  '', #where
		  '', #gruop by
		  'received DESC', #orderby
		  '1,5'); #limit (startingpoint,amount)

		// Now we will print our query to see what just happened
		print_r($GLOBALS['TYPO3_DB']->debug_lastBuiltQuery);
     
		$print='<br /><br />';
		if($GLOBALS['TYPO3_DB']->sql_num_rows($select)>0) {
		  while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($select) ) {
			$print .= $row['typo_page_title'].'<br />';
		  }
			echo $print;
		}
		*/
		
		####################################################  END --- MYSQL QUERY > TEMPLATE FILLING

		#echo '<br />';
		#echo '<pre>';
		#$info = $GLOBALS['TYPO3_DB'];
		#print_r($info);
		#var_dump($res_2);
		#global $TBE_TEMPLATE;
		#$TBE_TEMPLATE['template']['JScode'][]='akin was here :)';
		#print_r($TBE_TEMPLATE);
		#print_r($GLOBALS['MCONF']);
		#$this->MCONF = $GLOBALS['MCONF'];
		#print_r($this->MCONF);
		#echo 'TBE_TEMPLATE: '.$TBE_TEMPLATE;
		#echo 'temp_modPath: '.$GLOBALS["temp_modPath"];
		#echo '</pre>';
		
		
		#######################################################  DB  >  CONFIGURATION
		if(!isset($_GET['datepicker_from'])) $date_selected = 'no';




		#######################################################  SELECT EXTERNAL DATABASE ENTRY		
		// --> 1. select distinct page id and save in array
		// --> 2. select all data of a page id and save in array (sort)
		// --> 3. print top x results from array
		
		// --> 1. select distinct page id and save in array
		$array_pages_distinct = array();
		$array_pages_results = array();
		$array_comments_complete = array(); // all comments
		$counter = 0;

		#################  TYPO SQL
		/*
		$sql_2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		  '*', #select
		  'tx_th_feedback', #from
		  '', #where
		  '', #gruop by
		  '', #orderby
		  ''); #limit (startingpoint,amount)
		*/

		/*
		if ($date_selected == 'no')  {
			$sql_2 =  "SELECT * FROM $db_table WHERE received >= '2012-10-19' AND received <= '2012-10-25' ";
		}
		else {
			$sql_2 =  "SELECT * FROM $db_table WHERE received >= '2012-10-19' AND received <= '2012-10-25' ";
		}
		*/
		
		#$sql_2 =  "SELECT * FROM $db_table";
		$sql_2 =  "SELECT * FROM $db_table WHERE FROM_UNIXTIME(received)>='$date_from' AND FROM_UNIXTIME(received)<= '$date_to'";
		if ($date_selected == 'no') $sql_2 =  "SELECT * FROM $db_table";
		
		$results_2            =  mysql_query($sql_2) OR die(mysql_error());
		while ($row_2         =  mysql_fetch_object($results_2)) { // get db data as array
	
			// collect db data
			$db_id            =  $row_2->id; 
			$typo_page_id     =  $row_2->typo_page_id; 
			$helpful          =  $row_2->helpful;   
			$comment          =  $row_2->comment;   
			$user_ip          =  $row_2->user_ip;   
			$received         =  $row_2->received;   
			
			$offset=2*60*60; //converting 5 hours to seconds -> GMT+2
			$date             =  date("d.m.Y H:i",$received+$offset);
			
			// generate distinct pages array
			if(!in_array($typo_page_id,$array_pages_distinct)) $array_pages_distinct[]=$typo_page_id;
			
			// generate complete comments array
			if ($helpful == 2) { $array_comments_complete[$db_id][$typo_page_id]['id'] = $db_id; }
			if ($helpful == 2) { $array_comments_complete[$db_id][$typo_page_id]['typo_page_id'] = $typo_page_id; }
			if ($helpful == 2) { $array_comments_complete[$db_id][$typo_page_id]['comment'] = $comment; }
			if ($helpful == 2) { $array_comments_complete[$db_id][$typo_page_id]['user_ip'] = $user_ip; }
			if ($helpful == 2) { $array_comments_complete[$db_id][$typo_page_id]['date'] = $date; }
		
			#echo 'counter: '.$counter;
			if ($helpful == 2) $counter++;
		} // end while
		
		#echo '<pre>';
		#print_r($array_comments_complete);
		#echo '</pre>';
		
		// --> 2. select all data of a page id and save in array (sort)
		$counter_helpful=0;
		$array_ranking  = array(); // pages ranked (page_id, votes..)
		$array_comments = array(); // distinct comments (only page_id with respective comments)
		
		foreach ($array_pages_distinct as $pid) {
			$counter_pages=0;
			$counter_yes=0;
			$counter_no=0;
			$counter_yesbut=0;
		
			#################  TYPO SQL
			/*
			$sql_3 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			  '*', #select
			  'tx_th_feedback', #from
			  'typo_page_id='.$pid, #where
			  '', #gruop by
			  'received DESC', #orderby
			  ''); #limit (startingpoint,amount)
			*/

			#$sql_3            =  "SELECT * FROM $db_table WHERE typo_page_id='$pid'";
			$sql_3 =  "SELECT * FROM $db_table WHERE FROM_UNIXTIME(received)>='$date_from' AND FROM_UNIXTIME(received)<= '$date_to' AND typo_page_id='$pid' ";
			if ($date_selected == 'no') $sql_3 =  "SELECT * FROM $db_table WHERE typo_page_id='$pid'";
			$results_3        =  mysql_query($sql_3) OR die(mysql_error());
			while ($row_3     =  mysql_fetch_object($results_3)) { // get db data as array
			
				// collect db data
				$db_id            =  $row_3->id; 
				$typo_page_id     =  $row_3->typo_page_id;   
				$typo_page_title  =  $row_3->typo_page_title;   
				$helpful          =  $row_3->helpful;   
				$comment          =  $row_3->comment;   
				$user_ip          =  $row_3->user_ip;   
				$received         =  $row_3->received;   
				
				
				$sql_4            =  "SELECT * FROM $db_pages WHERE uid='$typo_page_id '";
				#$sql_4            =  "SELECT * FROM $db_table WHERE FROM_UNIXTIME(received)>='2012-10-22' AND FROM_UNIXTIME(received)< '2012-10-30' AND typo_page_id='$pid' ";
				$results_4        =  mysql_query($sql_4) OR die(mysql_error());
				$numRows_4        =  mysql_num_rows($results_4);
				while ($row_4     =  mysql_fetch_object($results_4)) { // get db data as array
				$typo_page_title  =  $row_4->title;
				}
				
				/*
				if ($numRows_4 == 0) {
					$sql_5            =  "SELECT * FROM $db_pages WHERE uid='$typo_page_id'";
					$results_5        =  mysql_query($sql_5) OR die(mysql_error());
					while ($row_5     =  mysql_fetch_object($results_5)) { // get db data as array
					$typo_page_title  =  $row_5->title;
					}
				}
				else {
					$sql_5            =  "SELECT * FROM $db_pages_trans WHERE pid='$typo_page_id' AND sys_language_uid='1'";
					$results_5        =  mysql_query($sql_5) OR die(mysql_error());
					while ($row_5     =  mysql_fetch_object($results_5)) { // get db data as array
					$typo_page_title  =  $row_5->title;
					}
				} // end if else
				*/
				
				
				
				
				// generate gmt date
				$offset=2*60*60; //converting 5 hours to seconds -> GMT+2
				$date             =  date("d.m.Y H:i",$received+$offset);
				
				// store results in array (page_id, typo_page_title)
				$array_pages_results[$pid]['page_id']=$typo_page_id;
				$array_pages_results[$pid]['typo_page_title']=$typo_page_title;
				
				// generate voting stats
				if ($helpful == 1) { $array_pages_results[$pid]['counter_yes']=$helpful; $counter_yes++; }
				if ($helpful == 0) { $array_pages_results[$pid]['counter_no']=$helpful; $counter_no++; }
				if ($helpful == 2) { $array_pages_results[$pid]['counter_yesbut']=$helpful; $counter_yesbut++; }
				
				if ($helpful == 2) { $array_comments[$pid][] = $comment.','.$date.','.$user_ip; }
			
				// increment page counter
				$counter_pages++;
			} // end while

			
			# -- debug2
			#echo '$numRows_4: '.$numRows_4.'<br />';
			#echo '$uid: '.$typo_page_id.'<br />';

			// store results in array (page counter)
			$array_pages_results[$pid]['counter_pages']=$counter_pages;
			$array_pages_results[$pid]['counter_yes']=$counter_yes;
			$array_pages_results[$pid]['counter_no']=$counter_no;
			$array_pages_results[$pid]['counter_yesbut']=$counter_yesbut;
			
			$array_ranking[$pid] = $array_pages_results[$pid]['counter_pages'];
		} // end foreach
		
		// sort ranking
		arsort($array_ranking);	
		
		#echo '<pre>';
		#print_r($array_pages_results);
		#echo '</pre>';
	
		// print html css table
		$content  = '<div class="th_container"><div id="statistics">';
		$content .= '<div class="row row_title">'.$LL_title_top.'</div><div class="row row_title">'.$LL_title_page_id.'</div><div class="row row_title row_text">'.$LL_title_page_title.'</div><div class="row row_title">'.$LL_title_yes.'</div><div class="row row_title">'.$LL_title_no.'</div><div class="row row_title">'.$LL_title_yesbut.'</div><div class="row row_title">'.$LL_title_total.'</div><div class="row row_title row_text">'.$LL_title_comments.'</div><div class="float_clear"></div></div>';
		
		$r_comments ='';
		$r_i=1; // rank incrementer
		$btn_i=1; // btn view comments  incrementer
		
		foreach ($array_ranking as $k => $v) {
		
			// get data from ranked and sorted array
			$r_pid       =  $array_pages_results[$k]['page_id'];
			$r_title     =  $array_pages_results[$k]['typo_page_title'];
			$r_yes       =  $array_pages_results[$k]['counter_yes'];
			$r_no        =  $array_pages_results[$k]['counter_no'];
			$r_yesbut    =  $array_pages_results[$k]['counter_yesbut'];
			$r_votes     =  $array_pages_results[$k]['counter_pages'];
		
			if (array_key_exists($r_pid,$array_comments)) {
				$comments_counted = count($array_comments[$r_pid]);
				for($i=0; $i < $comments_counted; $i++) {
					$array_explode = explode(",", $array_comments[$r_pid][$i]);
					$r_comments .= '<div class="comment div_'.$btn_i.'"><span class="date">Date: '.$array_explode[1].'</span><span class="ip"> (IP: '.$array_explode[2].')</span><br />'.$array_explode[0].'</div>';
				} // end for
			} // end if
			
			// generate link "view comments"
			if($r_yesbut == 0) $LL_btn_viewcomments = '';
		
			// calculate percentages
			$r_percent_total    = 100; // 100%
			$r_percent_yes      = round(((100 / $r_votes) * $r_yes)); // 100%
			$r_percent_no       = round(((100 / $r_votes) * $r_no)); // 100%
			$r_percent_yesbut   = round(((100 / $r_votes) * $r_yesbut)); // 100%
		
			// print html css table
			$content .= '<div class="th_container"><div class="row">'.$r_i.'</div><div class="row">'.$r_pid.'</div><div class="row row_text">'.$r_title.'</div><div class="row">'.$r_yes.' <span>('.$r_percent_yes.')%</span></div><div class="row">'.$r_no.' <span>('.$r_percent_no.')%</span></div><div class="row">'.$r_yesbut.' <span>('.$r_percent_yesbut.')%</span></div><div class="row">'.$r_votes.'</div><div class="row row_text">
			<a onclick="openComment('.$btn_i.');" class="btn_comment" id="btn_'.$btn_i.'">'.$LL_btn_viewcomments.'</a>&nbsp;</div><div class="float_clear"></div>'.$r_comments.'<div class="float_clear"></div></div>';
		
			// increment ranking top counter / comments btn
			$r_i++;
			$r_comments = '';
			$btn_i++;
			$LL_btn_viewcomments = $GLOBALS['LANG']->getLL('btn_viewcomments');
		}
		$content  .= '</div>';
		
		// print contents
		echo $content.'<br /><br /><br />';
		
		echo '<a href="http://www.typohosting.at" target="_blank"><img src="../typo3conf/ext/th_feedback/mod1/poweredby.png" width="175" height="16" /></a>';

	}	
}

		echo '<br />';
		echo '<pre>';
		#print_r($GLOBALS['TSFE']->sys_language_uid);
		#print_r($GLOBALS);
		echo '</pre>';

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/th_feedback/mod1/index.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/th_feedback/mod1/index.php']);
}






	// Make instance:
/** @var $SOBE tx_thfeedback_module1 */
$SOBE = t3lib_div::makeInstance('tx_thfeedback_module1');
$SOBE->init();

	// Include files?
foreach ($SOBE->include_once as $INC_FILE) {
	include_once($INC_FILE);
}

$SOBE->main();
$SOBE->printContent();

?>