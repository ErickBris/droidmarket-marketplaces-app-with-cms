<?php
$CI =& get_instance();
$CI->load->helper('ultils');
$CI->load->helper('file');

function getSettings($file){
	return object_2_array(json_decode(read_file($file, true)));
}

/*reset mail*/
$settings = array();
$settings = getSettings(EMAIL_SETTING_FILE);
if(!$settings){ 
	resetEmail();
}

$settings = getSettings(CONTACT_INFO_SETTING_FILE);
if(!$settings){ 
	resetContactInfo();
}

$settings = getSettings(GENERAL_SETTING_FILE);
if(!$settings){ 
	resetGeneral();
}

$settings = getSettings(CURRENCY_SETTING_FILE);
if(!$settings){ 
	resetCurrency();
}


$settings = getSettings(DEFAULT_LOCATION_FILE);
if(!$settings){ 
	resetDefaultLocation();
}


/*$settings= getSettings(RULES_SETTING_FILE);
if(!$settings){
	resetRules();
}*/
function resetGeneral(){
	/*Email settings*/
	$settings['facebook_fanpage']        = 'https://www.facebook.com/pages/LrandomDev/541746319279638?ref=hl';
	$settings['twitter']='https://twitter.com/lrandomdev';
	$settings['google_plus']='https://plus.google.com/u/1/116074713175395496992/posts';
	$settings['pinterest']='http://www.pinterest.com/lrandomDev';
	$settings['copyright']='&copy; Copyright 2013 by LrandomDev. All rights reserved.';
	$settings['ga_code']        = "<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-52513640-1', 'auto');
ga('send', 'pageview');
</script>";
resetSettings($settings,GENERAL_SETTING_FILE);
}

function resetEmail(){
	/*Email settings*/
	$settings['useragent']        = 'PHPMailer';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
	$settings['protocol']         = 'smtp';                   // 'mail', 'sendmail', or 'smtp'
	$settings['smtp_timeout']     = 5;                        // (in seconds)
	$settings['smtp_crypto']      = '';                       // '' or 'tls' or 'ssl'
	$settings['smtp_debug']       = 0;                        // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data
	$settings['wordwrap']         = true;
	$settings['wrapchars']        = 76;
	$settings['mailtype']         = 'html';                   // 'text' or 'html'
	$settings['charset']          = 'utf-8';
	$settings['validate']         = true;
	$settings['priority']         = 3;                        // 1, 2, 3, 4, 5
	$settings['crlf']             = "\n";                     // "\r\n" or "\n" or "\r"
	$settings['newline']          = "\n";                     // "\r\n" or "\n" or "\r"
	$settings['bcc_batch_mode']   = false;
	$settings['bcc_batch_size']   = 200;
	$settings['smtp_host']        = 'ssl://smtp.googlemail.com';
	$settings['smtp_user']        = '';
	$settings['smtp_pass']        = '';
	$settings['smtp_port']        = 465;
	$settings['from_email']       = '';
	$settings['from_user'] = SITE_NAME;
	$settings['mailpath']         = '';
	/*end email settings*/
	resetSettings($settings,EMAIL_SETTING_FILE);
}

function resetContactInfo(){
	/*Email settings*/
	$settings['email']         = 'demo@lrandomdev.com';
	$settings['phone']='012345678';
	$settings['fax']='012345678';
	$settings['skype']='lrandomdev';
	$settings['address']='to 32, khu 4a, phuong Ha Phong <br> Ha Long , Quang Ninh';
	/*end email settings*/
	resetSettings($settings,CONTACT_INFO_SETTING_FILE);
}

function resetCurrency(){
	$settings['position']  =  CURRENCY_SYMBOL_BEFORE;
	$settings['currency_symbol']='$';
	$settings['currency_id']=227;
	resetSettings($settings,CURRENCY_SETTING_FILE);
}

function resetDefaultLocation(){
	$settings['lat']  =  0;
	$settings['lng'] = 0;
	resetSettings($settings,DEFAULT_LOCATION_FILE);
}

function resetRules($settings,$file){
	$settings['rules']='';
	resetSettings($settings,RULES_SETTING_FILE);
}


function resetSettings($settings,$file){
	$json = json_encode($settings);
	write_file($file, $json);
}


function setSettings($settings,$file){
	$json=json_encode($settings);
	write_file($file,$json);
}
?>