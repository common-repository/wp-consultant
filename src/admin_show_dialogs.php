<?php
include_once("../soc.ini.php");
mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die (mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());
mysql_query("SET NAMES ".DB_CHARSET) or die(mysql_error());
//
mysql_query("UPDATE `".$table_prefix."soc_option` SET `option_value` = '1' WHERE `option_name` = 'operator_online'") or die(mysql_error());
mysql_query("UPDATE `".$table_prefix."soc_option` SET `option_value` = '".(time() + 15)."' WHERE `option_name` = 'last_online'")or die(mysql_error());
$sqlDialog = mysql_query("SELECT DISTINCT(`dialog_id`) FROM `".$table_prefix."soc_dialogs` ORDER BY `time` DESC");
while ($Dialog = mysql_fetch_array($sqlDialog))
{
	$Data = mysql_numrows(mysql_query("SELECT `id` FROM `".$table_prefix."soc_dialogs` WHERE `dialog_id` = '".$Dialog['dialog_id']."' and `status` = '1' and `author` NOT IN ('Consultant')"));
	$DialogTitle = explode("_", $Dialog['dialog_id']);
	print "<p style='margin: 0; padding: 0; line-height: 20px;'><a href='?page=consult_dialogs&dialog_id=".$Dialog['dialog_id']."'>Dialog at <b>".date("d.m.Y H:i", $DialogTitle[0])."</b>";
	if ($Data > 0) {
		print " (+".$Data.")";
		print "<embed src='/wp-content/plugins/wp-consultant/sound.mp3' hidden='true' autostart='true' loop='false'>";
	}
	print "</a></p>";
}
?>
