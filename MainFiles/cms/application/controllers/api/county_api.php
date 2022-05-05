<?php
require APPPATH.'/libraries/REST_Controller.php';
class county_api extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('county_model');
	}

	function county_get() 
	{ 
		$data=$this->county_model->get();
		if($data!=null){
			$this->response($data); 
		}else{
			$this->response(array('empty'=>'empty_data'));
		}
	} 

	function county_post() 
	{ 
		$data = array('this not available');
		$this->response($data); 
	} 

	function county_put() 
	{ 
		$data = array('this not available'); 
		$this->response($data); 
	} 

	function county_delete() 
	{ 
		$data = array('this not available');
		$this->response($data); 
	}  
}
?>