<?php
include_once("../soc.ini.php");
mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die (mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());
mysql_query("SET NAMES ".DB_CHARSET) or die(mysql_error());
//
if (isset($_POST['dialog_id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
	mysql_query("INSERT INTO `".$table_prefix."soc_dialogs` (`dialog_id`, `type`, `author`, `email`, `message`, `time`) VALUES ('".htmlspecialchars(trim($_POST['dialog_id']))."', '1', '".htmlspecialchars(trim($_POST['name']))."', '".$_POST['email']."', '".htmlspecialchars(trim($_POST['message']))."', '".time()."')") or die(mysql_error());
	$Admin = mysql_fetch_array(mysql_query("SELECT `option_value` FROM `".$table_prefix."soc_option` WHERE `option_name` = 'email'")) or die(mysql_error());
	$headers = "From: Online Consultant ".$_SERVER['SERVER_NAME']."\r\n";
	$headers .= "Content-Type: text/html; charset=utf-8\r\n";
	$message = "<p>For you on the website <a href='http://".$_SERVER['SERVER_NAME']."'>".$_SERVER['SERVER_NAME']."</a> user left a message <b>".$_POST['name']."</b>.</p>";
	$message .= "<p><b>Message:</b> ".$_POST['message']."</p>";
	$message .= "<p>&nbsp;</p>";
	$message .= "<p><b>User e-mail:</b> ".$_POST['email']."</p>";
	$message .= "<p>----------------------------------------------------------</p>";
	$message .= "<p>Online Consultant</p>";
	mail($Admin['option_value'], "New message from Online Consultant ".$_SERVER['SERVER_NAME'], $message, $headers);
}
?>