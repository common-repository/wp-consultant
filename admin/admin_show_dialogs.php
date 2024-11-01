<div class="wrap" id="center-panel">
	<h2>Dialogs WP Consultant</h2>
	<div id="admin_online"></div>
	<table border="1" width="100%" cellpadding="2" cellspacing="2">
		<tr>
			<td width="50%" valign="top">
				<div id="admin_dialogs"></div>
			</td>
			<td width="50%" valign="top">
				<?php
				if (isset($_GET['dialog_id'])) {
					print "
					<div id='message_block'></div>
					<form id='answerForm'>
						<input type='hidden' name='dialog_id' id='dialog_id' value='".$_GET['dialog_id']."'>
						<input type='text' name='message' id='message_admin' value=''>
						<input type='submit' name='send_message' value='Send'>
					</form>
					";
				} else {
					print "<p align='center'>Select dialog to view from the left column</p>";
				}
				?>
			</td>
		</tr>
	</table>
</div>