<?php
require APPPATH.'/libraries/REST_Controller.php';
class common_api extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('settings');
	}

	function rules_get() 
	{ 
		$settings=getSettings(RULES_SETTING_FILE);
		if($settings!=null){
			$data['rules']=$settings['rules'];
			$this->load->view('rules', $data);
		}else{
			$this->response(array('empty'=>'empty_data'));
		}
	} 
}
?>