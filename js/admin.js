function ShowListDialogs()  
{  
	jQuery.ajax({  
		url: "/wp-content/plugins/wp-consultant/src/admin_show_dialogs.php",  
		cache: false,  
		success: function(html){  
			jQuery("#admin_dialogs").html(html);  
		}  
	});  
}
//
function ShowCurrentDialog()  
{
	jQuery.ajax({
		type: "POST",
		url: "/wp-content/plugins/wp-consultant/src/admin_view_dialog.php",
		data: "dialog_id="+jQuery("#dialog_id").val(), 
		cache: false,  
		success: function(html){  
			jQuery("#message_block").html(html);  
		}
	}); 
}
//
jQuery(document).ready(function(){
	jQuery('#answerForm').submit(function(){
		jQuery.ajax({  
			type: "POST",  
			url: "/wp-content/plugins/wp-consultant/src/admin_send_message.php",
			data: "dialog_id="+jQuery("#dialog_id").val()+"&message="+jQuery("#message_admin").val(), 
			success: function(html){  
				jQuery("#message_block").html(html);  
			}  
		});
		document.getElementById("message_admin").value = "";
		return false;
	});
	ShowListDialogs();
	setInterval('ShowListDialogs()', 3000);
	ShowCurrentDialog();
	setInterval('ShowCurrentDialog()', 3000);
});