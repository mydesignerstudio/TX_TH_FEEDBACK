<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_thfeedback_pi1.php', '_pi1', 'list_type', 1);
$TYPO3_CONF_VARS['FE']['eID_include']['sfajax'] = 'EXT:th_feedback/pi1/db_update.php';
?>