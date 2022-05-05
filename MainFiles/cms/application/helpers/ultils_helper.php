<?php
function trim_text($input, $length, $ellipses = true, $strip_html = true) {
	$input = word_limiter($input, $length);
	if ($strip_html) {
		$input = strip_tags(html_entity_decode(htmlspecialchars_decode($input), ENT_NOQUOTES, 'utf-8'));
	}
	if ($ellipses) {
		$trimmed_text .= '...';
	}
	return $input;
}

function show_vi_current_time() {
	$str_in = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
	$str_out = array("Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy", "Chủ nhật");
	$time = gmdate("D, d/m/Y, H:i", time() + 7 * 3600);
	$time = str_replace($str_in, $str_out, $time);
	return $time;
}


function show_vi_time_from_my_sql($str_time_in) {
	$datePost = new DateTime($str_time_in);
	$time = $datePost -> format('D, d/m/Y, H:i');
	$str_in = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
	$str_out = array("Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy", "Chủ nhật");
	$time = str_replace($str_in, $str_out, $time);
	return $time;
}


function show_format_vi_date_time($str_time) {
	return date('d-m-Y H:i:s', strtotime($str_time));
}

function rename_upload_file($file_name){
	return md5(time().rand(0,100000)).'.'.pathinfo($file_name, PATHINFO_EXTENSION);
}

function encrypt_pwd($pwd){
	return md5(md5(md5($pwd)));
}


function get_facebook_avt($fbId, $width, $height) {
	return "http://graph.facebook.com/" . $fbId . "/picture?width=" . $width
	."&height=" .$height;
}


function delete_directory($dir) {
	if (!file_exists($dir)) return true;
	if (!is_dir($dir) || is_link($dir)) return unlink($dir);
	foreach (scandir($dir) as $item) {
		if ($item == '.' || $item == '..') continue;
		if (!deleteDirectory($dir . "/" . $item)) {
			chmod($dir . "/" . $item, 0777);
			if (!deleteDirectory($dir . "/" . $item)) return false;
		};
	}
	return true;
}
function ban_word($str){
    $ban_word =  BAN_WORD;// Lay tu cam trong csdl
    if($ban_word != NULL) {
    	$word = explode(",", $ban_word);
    	foreach ($word as $key => $value){
    		$str = preg_replace('/'.$value.'/i', "***", $str);
    	}
    }
    return $str;
}

function get_current_url()
{
	$CI =& get_instance();

	$url = $CI->config->site_url($CI->uri->uri_string());
	return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}

function object_2_array($d) {
	if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
        	return $d;
        }
    }
    function array_2_object($d) {
    	if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return (object) array_map(__FUNCTION__, $d);
        }
        else {
            // Return object
        	return $d;
        }
    }


    function create_dir_upload(){
    	$date_string = date("Y-n-j");
    	$year_string = date("Y");
    	$dir = 'uploads/'.date("W", strtotime($date_string)) . '_' . $year_string;
    	if (!is_dir($dir)) 
    	{ 
    		mkdir($dir, 0777);
    	}
    	return $dir;
    }

    function create_thumb_dir_upload($parent_link){
    	$date_string = date("Y-n-j");
    	$year_string = date("Y");
    	$dir = $parent_link;
    	if (!is_dir($dir)) 
    	{ 
    		mkdir($dir, 0777);
    	}
    	return $dir;
    }

    function create_sub_dir_upload($parent_link){
    	$date_string = date("Y-n-j");
    	$year_string = date("Y");
    	$dir = $parent_link.date("W", strtotime($date_string)) . '_' . $year_string;
    	if (!is_dir($dir)) 
    	{ 
    		mkdir($dir, 0777);
    	}
    	return $dir;
    }


    function create_slug($title){
         $string1 = str_replace("ü", "ue", $title);   // replace $_POST['titel']
         $string2 = str_replace("Ü", "Ue", $string1);
         $string3 = str_replace("ö", "oe", $string2);
         $string4 = str_replace("Ö", "oe", $string3);
         $string5 = str_replace("ä", "ae", $string4);
         $string6 = str_replace("Ä", "Ae", $string5);
         $string7 = str_replace("g", "g", $string6);
         $string8 = str_replace("G", "G", $string7);
         $string9 = str_replace("ç", "C", $string8);
         $string10 = str_replace("Ç", "C", $string9);
         $string11 = str_replace("s", "s", $string10);
         $string12 = str_replace("S", "S", $string11);
         $string13 = str_replace("i", "i", $string12);
         $string14 = str_replace("I", "I", $string13);
         $string15 = str_replace("ß", "ss", $string14);
         $string16 = preg_replace("/[^A-Z a-z]/", "*", $string15);
         $string17 = str_replace("*", "", $string16); 
         $string18 = str_replace(" ", "-", $string17); 
         return $string18;
     }
     ?>