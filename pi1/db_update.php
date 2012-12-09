<?php
#######################################################  DB  >  OPEN CONNECTION
// DATABASE CONFIGURATION
/*
error_reporting(E_ALL);
define('MYSQL_HOST','localhost');
define('MYSQL_USER','root');
define('MYSQL_PASS','');
define('MYSQL_DATABASE','md_cms');

// start connection
$db_connection = @mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS) OR
	die("Keine Verbindung zur Datenbank. Fehlermeldung:".mysql_error());

// select database
$db_selection = mysql_select_db(MYSQL_DATABASE) OR
	die("Konnte Datenbank nicht benutzen, Fehlermeldung: ".mysql_error());
*/

if (!defined ('PATH_typo3conf')) 	die ('Access denied: eID only.');
require_once(PATH_tslib . 'class.tslib_pibase.php');

class tx_th_feedback_eid extends tslib_pibase {
	var $prefixId      = 'tx_th_feedback_eid';		// Same as class name
	var $scriptRelPath = 'db_update.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'th_feedback';	// The extension key.

	function eid_main() {
		#$GLOBALS['TSFE']->fe_user = tslib_eidtools::initFeUser();
		tslib_eidtools::connectDB();
		#var_dump($GLOBALS['TSFE']->fe_user);
	
		#######################################################  DB  >  UPDATE
		// variables
		$helpful             =  $_GET['helpful'];
		$comment             =  htmlentities($_GET['comment'], ENT_QUOTES, "UTF-8");
		$typo_page_id        =  $_GET['page_id'];
		$typo_page_title     =  $_GET['page_title'];
		$user_ip             =  $_SERVER['REMOTE_ADDR']; // '31.47.4.128';
		$timestamp           =  time(); // collect unix timestamp
		
		#######################################################  DB  >  CONFIGURATION
		// define database table
		$db_table  =  "tx_th_feedback";
		
		#######################################################  DB  >  CHECK FOR DUPLICATE IP
		$sql      =  "SELECT * FROM $db_table WHERE user_ip='$user_ip' AND typo_page_id='$typo_page_id' ";
		$results  =  mysql_query($sql) OR die(mysql_error());
		$numRows  =  mysql_num_rows($results);
		
		if ($numRows > 0) {
			#echo 'numRows: '.$numRows.'<br />';
			echo 'duplicate_yes';
		}
		else {
			// do database query
			$sql  =  "INSERT INTO $db_table
					  (
						  typo_page_id,
						  typo_page_title,
						  helpful,
						  comment,
						  user_ip,
						  lastmod,
						  received
					  )
					  VALUES
					  (
						  '$typo_page_id',
						  '$typo_page_title',
						  '$helpful',
						  '$comment',
						  '$user_ip',
						  '$timestamp',
						  '$timestamp'
					  )"
					  OR die(mysql_error());
			$results  =  mysql_query($sql) OR die(mysql_error());
			echo 'duplicate_no';
		}
	
	}
}

$extensionkey = t3lib_div::makeInstance('tx_th_feedback_eid');
$extensionkey->eid_main();

#######################################################  DB CONNECTION :: CLOSE
// close database connection
#mysql_close($db_connection) OR
#	die("Konnte Verbindung mit Datenbank nicht beenden. Fehlermeldung:".mysql_error());
#echo "Verbindung mit Datenbank beendet<br />\n";;


?>