<div class="wrap" id="center-panel">
	<h2>Options WP Consultant</h2>
	<form action="admin.php?page=consult_edit&action=submit" method="post" enctype="multipart/form-data">
		<input type="hidden" name="type_submit" value="save">
		<table border="0" width="100%" cellpadding="2" cellspacing="2">
			<tr>
				<td width="25%">E-mail for notifications:</td>
				<td width="75%"><input type="text" name="email" id="email" value="<?php echo $this->data['email']['option_value']; ?>" style="clear: both; width: 300px;"></td>
			</tr>
			<?php
			if ($this->data['image']['option_value'] != "") {
				print "
				      <tr>
				          <td valign='top'>Current consultant image:</td>
				          <td><img align='left' src='../../wp-content/wp_consultant/".$this->data['image']['option_value']."' border='0'>&nbsp;<input name='image_del' type='checkbox' value='1'>&nbsp;delete</td>
			          </tr>
			          <input type='hidden' name='image_del_flag' value='0'>
				      ";
			} else {
				print "<input type='hidden' name='image_del' value='0'>";
			}
			?>
			<tr>
				<td>Load new consultant image:</td>
				<td><input type="file" name="image" size="50" accept="image/"><br>gif, jpg, png, jpeg, GIF, JPG, PNG, JPEG<br>Recommended size: 36 x 36 px</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="send" id="save" value="Save"></td>
			</tr>
		</table>
	</form>
</div>