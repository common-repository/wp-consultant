<?php
/*
 * Template Name: Form for WP Consultant
 */
?>
<div id="consult">
<?php
if (empty($_SESSION['dialog_id'])) {
	$rand = rand('0', '9999');
	$_SESSION['dialog_id'] = time()."_".$rand;
}
?>
	<div id="consult_close"></div>
	<div id="consult_open"></div>
	<div id="consult_block">
	<?php
	$StatusCheck = $wpdb->get_row("SELECT `option_value` FROM `".$wpdb->prefix."soc_option` WHERE `option_name` = 'operator_online' LIMIT 1", ARRAY_A);
	$LastOnline = $wpdb->get_row("SELECT `option_value` FROM `".$wpdb->prefix."soc_option` WHERE `option_name` = 'last_online' LIMIT 1", ARRAY_A);
	if ($StatusCheck['option_value'] == 0 || $LastOnline['option_value'] < time()) {
		$wpdb->query("UPDATE `".$wpdb->prefix."soc_option` SET `option_value` = '0' WHERE `option_name` = 'operator_online'");
		// Consultant offline, but dialog exist
		$DialogCheck = $wpdb->get_row("SELECT `id` FROM `".$wpdb->prefix."soc_dialogs` WHERE `dialog_id` = '".$_SESSION['dialog_id']."' LIMIT 1");
		if ($DialogCheck) {
			print "
			<div id='message_zone'>
				<div id='message_block'></div>
			</div>
			<form id='consultForm'>
				<input type='hidden' name='dialog_id' id='dialog_id' value='".$_SESSION['dialog_id']."'>
				<textarea name='message' id='message' onFocus='if (this.value==\"Enter your message ...\") { this.value=\"\"; }' onBlur='if (this.value==\"\") { this.value=\"Enter your message ...\"; }'>Enter your message ...</textarea>
				<input type='submit' name='send_message' id='send_message' value='' disabled='disabled'>
			</form>
			";
		} elseif (!$DialogCheck) {
			// Consultant offline, dialog not exist, show mail form
			print "
			<div id='message_zone'>
				<div id='message_offline_block'>
					<h2>The consultant is offline</h2>
					<h1>Send your question</h1>
					<p>Name:</p>
					<p><input type='text' name='offline_name' id='offline_name' value=''></p>
					<p>E-mail:</p>
					<p><input type='text' name='offline_email' id='offline_email' value=''></p>
				</div>
			</div>
			<form id='consultMailSend'>
				<input type='hidden' name='dialog_id' id='dialog_id' value='".$_SESSION['dialog_id']."'>
				<textarea name='offline_message' id='offline_message' onFocus='if (this.value==\"Enter your message ...\") { this.value=\"\"; }' onBlur='if (this.value==\"\") { this.value=\"Enter your message ...\"; }'>Enter your message ...</textarea>
				<input type='submit' name='send_message' id='send_message' value='' disabled='disabled'>
			</form>
			";
		}
	} elseif ($StatusCheck['option_value'] == 1) {
		// Consultant online
		print "
		<div id='message_zone'>
			<div id='message_block'></div>
		</div>
		<form id='consultForm'>
			<input type='hidden' name='dialog_id' id='dialog_id' value='".$_SESSION['dialog_id']."'>
			<textarea name='message' id='message' onFocus='if (this.value==\"Enter your message ...\") { this.value=\"\"; }' onBlur='if (this.value==\"\") { this.value=\"Enter your message ...\"; }'>Enter your message ...</textarea>
			<input type='submit' name='send_message' id='send_message' value='' disabled='disabled'>
		</form>
		";
	}
	?>
	</div>
</div>