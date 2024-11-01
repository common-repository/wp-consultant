<?php
include_once("../soc.ini.php");
mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die (mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());
mysql_query("SET NAMES ".DB_CHARSET) or die(mysql_error());
//
mysql_query("UPDATE `".$table_prefix."soc_dialogs` SET `status` = '0' WHERE `dialog_id` = '".$_POST['dialog_id']."' and `author` NOT IN ('Consultant')") or die (mysql_error());
$sqlMessages = mysql_query("SELECT `type`, `author`, `message`, `time`, `status` FROM `".$table_prefix."soc_dialogs` WHERE `dialog_id` = '".$_POST['dialog_id']."'") or die (mysql_error());
if (mysql_num_rows($sqlMessages) > 0) {
	while ($Messages = mysql_fetch_array($sqlMessages))
	{
		if ($Messages['author'] == "Consultant") { $author = "You"; } elseif ($Messages['author'] == "You") { $author = "User"; } else { $author = $Messages['author']; }
		print "<p>[".date("H:i:s", $Messages['time'])."] <u>".$author.":</u> ".$Messages['message']."</p>";
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
