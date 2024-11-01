<?php
include_once("../soc.ini.php");
mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die (mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());
mysql_query("SET NAMES ".DB_CHARSET) or die(mysql_error());
//
if (isset($_POST['message']) && $_POST['message'] != "" && isset($_POST['dialog_id']) && $_POST['dialog_id'] != "") {
	mysql_query("INSERT INTO `".$table_prefix."soc_dialogs` (`dialog_id`, `type`, `author`, `message`, `time`) VALUES ('".$_POST['dialog_id']."', '0', 'Consultant', '".htmlspecialchars(trim($_POST['message']))."', '".time()."')") or die(mysql_error());;
}
?>
