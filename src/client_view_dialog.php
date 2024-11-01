<?php
include_once("../soc.ini.php");
mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die (mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());
mysql_query("SET NAMES ".DB_CHARSET) or die(mysql_error());
session_start();
//
$sqlMessages = mysql_query("SELECT `id`, `type`, `author`, `message`, `time`, `status` FROM `".$table_prefix."soc_dialogs` WHERE `dialog_id` = '".$_SESSION['dialog_id']."'") or die(mysql_error());
$message_count = mysql_num_rows($sqlMessages);
if ($message_count > 0) {
	while ($Messages = mysql_fetch_array($sqlMessages))
	{
		if ($Messages['status'] == 1 && $Messages['author'] == "Consultant") {
			mysql_query("UPDATE `".$table_prefix."soc_dialogs` SET `status` = '0' WHERE `id` = '".$Messages['id']."' and `author` = 'Consultant' LIMIT 1") or die(mysql_error());
		}
		if ($Messages['author'] != "Consultant") {
			print "
			<p style='color: #b60909; text-align: left; font-family: Arial; font-weight: bold; font-size: 10pt;'>You:</p>
			<p style='text-align: left; font-style: italic; color: black; font-size: 10pt;'>".$Messages['message']."</p>
			<hr style='width: 100%; margin-top: 5px; margin-bottom: 5px; border: 1px solid #a39f9f;'>
			";
		} elseif ($Messages['author'] == "Consultant") {
			$ConImg = mysql_fetch_array(mysql_query("SELECT `option_value` FROM `".$table_prefix."soc_option` WHERE `option_name` = 'consultant_image' LIMIT 1"));
			if ($ConImg['option_value'] != "") {
				print "<div style='float: left; width: 36px; height: 36px; position: relative;'><img src='/wp-content/wp_consultant/".$ConImg['option_value']."' width='36' height='36'></div>";
			}
			print "
			<p style='color: #01b00e; text-align: right; font-family: Arial; font-weight: bold; font-size: 10pt;'>Consultant:</p>
			<p style='text-align: right; font-style: italic; color: black; font-size: 10pt;'>".$Messages['message']."</p>
			<hr style='width: 100%; margin-top: 5px; margin-bottom: 5px; border: 1px solid #a39f9f;'>
			";
		}
		print "
		<script>
		var txt = document.getElementById(\"message_block\");
		var hg = txt.clientHeight + txt.scrollTop;
		txt.scrollTop = hg;
		</script>
		";
	}
}
?>
