<?php
function sendTelegramMessage($pm) {
	global $vars;
	$application_chatid = mysql_fetch_array( select_query('tbladdonmodules', 'value', array('module' => 'wt_note', 'setting' => 'chatid') ), MYSQL_ASSOC );
	$application_botkey = mysql_fetch_array( select_query('tbladdonmodules', 'value', array('module' => 'wt_note', 'setting' => 'key') ), MYSQL_ASSOC );
	$chat_id 		= $application_chatid['value'];
	$botToken 		= $application_botkey['value'];

	$data = array(
		'chat_id' 	=> $chat_id,
		'text' 		=> $pm . "time: ".date("Y/m/d h:i:s")."\n\n🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸\n" . base64_decode("V0hNQ1MgVGVsZWdyYW0gTm90aWZpY2F0aW9uIE1vZHVsZSBCeSBAUlRFVE0gfHwgQW1pciBob3NzZWlu")
	);

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://api.telegram.org/bot$botToken/sendMessage");
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_exec($curl);
	curl_close($curl);
}

function wt_note_ClientAdd($vars) {
	global $customadminpath, $CONFIG;
	sendTelegramMessage("یک کاربر جدید در سیستم ثبت شد \n🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸 \n\n". $CONFIG['SystemURL'].'/'.$customadminpath.'/clientssummary.php?userid='.$vars['userid']);
}

function wt_note_InvoicePaid($vars) {
	global $customadminpath, $CONFIG;
	sendTelegramMessage("یک فاکتور با مشخصات زیر پرداخت شد \n🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸 \n\n شناسه فاکتور : $vars[invoiceid] \n\n مبلغ : $vars[total] \n\n". $CONFIG['SystemURL'].'/'.$customadminpath.'/invoices.php?action=edit&id='.$vars['invoiceid']);
}

function wt_note_TicketOpen($vars) {
	global $customadminpath, $CONFIG;
	sendTelegramMessage("یک تیکت جدید با مشخصات زیر ایجاد شد \n🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸 \n\n شناسه تیکت : $vars[ticketid] \n\n دپارتمان : $vars[deptname] \n\n عنوان تیکت : $vars[subject] \n\n". $CONFIG['SystemURL'].'/'.$customadminpath.'/supporttickets.php?action=viewticket&id='.$vars['ticketid']);
}

function wt_note_TicketUserReply($vars) {
	global $customadminpath, $CONFIG;
	sendTelegramMessage("پاسخ جدید به تیکت با مشخصات زیر توسط کاربر ارسال شد \n🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸 \n\n شناسه تیکت : $vars[ticketid] \n\n دپارتمان : $vars[deptname] \n\n عنوان تیکت : $vars[subject] \n\n". $CONFIG['SystemURL'].'/'.$customadminpath.'/supporttickets.php?action=viewticket&id='.$vars['ticketid']);

}

function wt_note_LogActivity($vars) {
	global $customadminpath, $CONFIG;
	sendTelegramMessage("new activity\n🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸🔹🔸 \n\n username : $vars[user] \n\n ip : $vars[ipaddress] \n\n description : $vars[description] \n\n");

}

add_hook("ClientAdd",1,"wt_note_ClientAdd");
add_hook("InvoicePaid",1,"wt_note_InvoicePaid");
add_hook("TicketOpen",1,"wt_note_TicketOpen");
add_hook("TicketUserReply",1,"wt_note_TicketUserReply");
add_hook("LogActivity",1,"wt_note_LogActivity");