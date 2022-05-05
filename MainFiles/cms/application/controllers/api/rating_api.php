<?php
require APPPATH.'/libraries/REST_Controller.php';
class rating_api extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('rating_model');
		$this->load->helper('Ultils');
	}

	function rating_get() 
	{ 
		$this->load->model('rating_model');
		$first=$this->get('first');
		$offset=$this->get('offset');
		$order=array('rating.created_at'=>'DESC');
		$where=array();
		$like=array();

		$id=$this->get('id');
		if($id!=null){
			$where['id']=$id;
		}

		$product_id=$this->get('product_id');
		if($product_id!=null){
			$where['product_id']=$product_id;
		}

		$product_user_id=$this->get('product_user_id');
		if($product_user_id!=null){
			$where['product_user_id']=$product_user_id;
		}
		
		$data=$this->rating_model->get('
			users.id as user_id,
			users.user_name as user_name,
			users.avt,
			rating.product_user_id,
			rating.point,
			rating.id,
			rating.comment,
			rating.product_id,
			rating.created_at as created_at,rating.updated_at as updated_at',$where,$like,$first,$offset,$order);
		if($data!=null){
			$this->response($data); 
		}else{
			$this->response(array('empty'=>'empty_data'));
		}
	} 

	function rating_post(){
		$id=$this->post('id');
		if(isset($_FILES['avt'])){
			$config['upload_path'] = 'uploads/avts';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|JPG|JPEG|GIF|PNG';
			$config['max_size']	= '2000';
			$this->load->library('upload', $config);
			$filename=$_FILES['avt']['name'];
			$_FILES['avt']['name']=rename_upload_file($filename);	
			if ($this->upload->do_upload('avt'))
			{
				try {
					$user=$this->rating_model->get_by_id($id);
					unlink($user[0]->avt);
				} catch (Exception $e) {
					
				}
				$image_path='uploads/avts/'.$_FILES['avt']['name'];
				$data=array('avt'=>$image_path);
				$this->rating_model->update($data,array('id'=>$id));	
			}
		}
		$phone=$this->post('phone');
		$address=$this->post('address');
		$website=$this->post('website');
		$user=$this->rating_model->get_by_exact_phone_and_diff_id($id,$phone);
		if($user==null){
			$user=$this->rating_model->get_by_exact_web_and_diff_id($id ,$website);
			if($user==null){
				$data=array('phone'=>$phone,'address'=>$address,'website'=>$website);
				$this->rating_model->update($data,array('id'=>$id));	
				$this->response($this->rating_model->get_by_id($id));
				exit();
			}
		}
		$this->response(array('empty'=>'empty_data'));
	}

	function rating_put() 
	{ 
		$data = array('this not available'); 
		$this->response($data); 
	} 

	function rating_delete() 
	{ 
		$data = array('this not available');
		$this->response($data); 
	}  


	function user_avg_rate_get(){
		$user_id=$this->get('user_id');
		$this->load->model('rating_model');
		$rating = $this->rating_model->get_by_user_product_id($user_id);
		if($rating!=null){
			$this->response(array("rating"=>$rating));
		}else{
			$this->response(array("rating"=>null));
		}	
	}
}
?>