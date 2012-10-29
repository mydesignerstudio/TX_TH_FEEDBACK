<?php
#######################################################  DB  >  OPEN CONNECTION
// DATABASE CONFIGURATION
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

#######################################################  DB  >  UPDATE
// variables
$helpful             =  $_GET['helpful'];
$comment             =  htmlentities($_GET['comment'], ENT_QUOTES, "UTF-8");
$typo_page_id        =  $_GET['page_id'];
$typo_page_title     =  $_GET['page_title'];
$user_ip             =  '31.47.4.128'; //$_SERVER['REMOTE_ADDR'];
$timestamp           =  time(); // collect unix timestamp

#######################################################  DB  >  CONFIGURATION
// define database table
$db_table  =  "tx_th_feedback";

#######################################################  DB  >  CHECK FOR DUPLICATE IP
$sql      =  "SELECT * FROM $db_table WHERE user_ip='$user_ip' AND typo_page_id='$typo_page_id' ";
$results  =  mysql_query($sql) OR die(mysql_error());
$numRows  =  mysql_num_rows($results);

if ($numRows < 0) {
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

#######################################################  DEBUGGING
/*
echo '<h1>DEBUGGING</h1>';
echo 'helpful: '.$helpful.'<br />';
echo 'user_ip: '.$user_ip.'<br />';
echo 'comment: '.$comment.'<br />';
*/

#######################################################  DB CONNECTION :: CLOSE
// close database connection
mysql_close($db_connection) OR
	die("Konnte Verbindung mit Datenbank nicht beenden. Fehlermeldung:".mysql_error());
#echo "Verbindung mit Datenbank beendet<br />\n";;


?>