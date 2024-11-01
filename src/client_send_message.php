<?php
include_once("../soc.ini.php");
mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die (mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());
mysql_query("SET NAMES ".DB_CHARSET) or die(mysql_error());
//
if (isset($_POST['type']) && $_POST['type'] == 0) {
	if ($_POST['message'] != "") {
		mysql_query("INSERT INTO `".$table_prefix."soc_dialogs` (`dialog_id`, `type`, `author`, `message`, `time`) VALUES ('".htmlspecialchars(trim($_POST['dialog_id']))."', '0', 'You', '".htmlspecialchars(trim($_POST['message']))."', '".time()."')") or die(mysql_error());
		print "
		<script type='text/javascript'>
		var txt = document.getElementById('message_block');
		var hg = txt.clientHeight + txt.scrollTop;
		txt.scrollTop = hg;
		</script>
		";
	}
}
?>