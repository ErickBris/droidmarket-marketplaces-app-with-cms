<?php
  /**
  * 
  */
  class LangLoader
  {
  	
  	function init()
  	{
  		$ci =& get_instance();
  		$ci->load->helper('language');
  		$ci->lang->load('msg',$ci->config->item('language'));
  	}
  }
  ?>