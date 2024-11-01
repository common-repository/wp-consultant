function ShowDialog()  
{  
	jQuery.ajax({
		url: "/wp-content/plugins/wp-consultant/src/client_view_dialog.php",  
		cache: false,  
		success: function(html){  
			jQuery("#message_block").html(html);  
		}		
	});
}
jQuery(document).ready(function(){
	document.getElementById("send_message").disabled = 0;
	var intShowDialog = 0;
	var intShowOnlineStatus = 0;
	jQuery('#consult_close').click(function(){
		document.getElementById("consult_open").style.display = "block";
		document.getElementById("consult_block").style.display = "block";
		document.getElementById("consult_close").style.display = "none";
		ShowDialog();
		intShowDialog = setInterval('ShowDialog()',3000);
		ShowOnlineStatus();
		intShowOnlineStatus = setInterval('ShowOnlineStatus()',3000);
	});
	jQuery('#consult_open').click(function(){
		document.getElementById("consult_close").style.display = "block";
		document.getElementById("consult_open").style.display = "none";
		document.getElementById("consult_block").style.display = "none";
		clearInterval(intShowDialog);
		clearInterval(intShowOnlineStatus);
	});
	jQuery('#consultForm').submit(function(){
		jQuery.ajax({  
			type: "POST",  
			url: "/wp-content/plugins/wp-consultant/src/client_send_message.php",  
			data: "dialog_id="+jQuery("#dialog_id").val()+"&message="+jQuery("#message").val()+"&type=0",  
			success: function(html){  
				jQuery("#message_block").html(html);  
			}  
		});
		document.getElementById("message").value = "";
		return false;  
	});
	jQuery('#consultMailSend').submit(function(){
		var name = document.getElementById("offline_name").value;
		var email = document.getElementById("offline_email").value;
		var message = document.getElementById("offline_message").value;
		var regexp = /^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i;
		if (name == "") {
			alert("You did not enter a name.");
			return false;
		} else if (email == "" || (email != "" && regexp.test(email) == false)) {
			alert("E-mail is incorrect.");
			return false;
		} else if (message == "" || message == "Enter your message ...") {
			alert("You have not entered the message.");
			return false;
		} else {
			jQuery.ajax({  
				type: "POST",  
				url: "/wp-content/plugins/wp-consultant/src/client_send_email.php",  
				data: "dialog_id="+jQuery("#dialog_id").val()+"&name="+ name +"&email="+ email +"&message="+ message,
				success: function(html){  
					alert("Thanks for your message!");
				}  
			});
			return false;
		}
	});
});