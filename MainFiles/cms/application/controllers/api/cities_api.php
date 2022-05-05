<?php
require APPPATH.'/libraries/REST_Controller.php';
class cities_api extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('cities_model');
	}

	function cities_get() 
	{ 
		$data=$this->cities_model->get();
		if($data!=null){
			$this->response($data); 
		}else{
			$this->response(array('empty'=>'empty_data'));
		}
	} 

	function cities_by_county_id_get(){
		$county_id=$this->get('id');
		$data=$this->cities_model->get_by_county_id($county_id);
		if($data!=null){
			$this->response($data); 
		}else{
			$this->response(array('empty'=>'empty_data'));
		}	
	}

	function cities_post() 
	{ 
		$data = array('this not available');
		$this->response($data); 
	} 

	function cities_put() 
	{ 
		$data = array('this not available'); 
		$this->response($data); 
	} 

	function cities_delete() 
	{ 
		$data = array('this not available');
		$this->response($data); 
	}  
}
?>