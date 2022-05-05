<?php
function send_verified_mail($verified_code,$receive_email){
	$CI =& get_instance();
	$CI->load->helper('settings');
	$configs = getSettings(EMAIL_SETTING_FILE);
	$CI->load->library('email',$configs);
	$CI->email->initialize($configs);
	$subject = $CI->lang->line('verified_code_label');
	$body ='<p>'.$CI->lang->line('verified_msg').'&nbsp;<b>'.
	$verified_code.'</b><p>';
	$result = $CI->email
	->from($configs['from_email'],$configs['from_user'])
	->to($receive_email)
	->subject($subject)
	->message($body)
	->send();
}


function send_enquiry($message,$receive_email="luann4099@gmail.com",$reply_to="beginlive@gmail.com",$user_name){
	$CI =& get_instance();
	$CI->load->helper('settings');
	$configs = getSettings(EMAIL_SETTING_FILE);
	$configs['from_user']=$user_name;
	$configs['from_email']=$reply_to;
	$CI->load->library('email',$configs);
	$CI->email->initialize($configs);
	$subject = $CI->lang->line('subject_enquiry_msg').' '.$user_name;
	$body ='<p>'.$user_name.'&nbsp;'.$CI->lang->line('enquiry_msg').'<p>'.'<p>---------</p>'.$message.'<p>---------</p>'.$CI->lang->line('response_enquiry_msg');
	$result = $CI->email
	->from($configs['from_email'],$configs['from_user'])
	->to($receive_email)
	->subject($subject)
	->message($body)
	->reply_to($reply_to)
	->send();
}
?>