<?php
require APPPATH.'/libraries/REST_Controller.php';
class products_api extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->helper('Ultils');
	}

	function products_get() 
	{ 
		$first=$this->input->get('first');
		$offset=$this->input->get('offset');
		$categories_id=$this->input->get('categories_id');
		$sort_by=$this->input->get('sort_by');
		$where=array();
		$like=array();
		$order=array('products.updated_at'=>'DESC');
		$where['users.activated']=1;
		$where['products.activated']=1;

		if($categories_id!=null){
			$where['categories_id']=$categories_id;
		}

		$county_id=$this->input->get('county_id');
		if($county_id!=null){
			$where['products.county_id']=$county_id;
		}

		$cities_id=$this->input->get('cities_id');
		if($cities_id!=null){
			$where['products.cities_id']=$cities_id;
		}

		$product_id=$this->input->get('product_id');
		if($product_id!=null){
			$where['products.id']=$product_id;	
		}

		if($sort_by!=null){
			$order=array('products.updated_at'=>$sort_by);
		}

		$id=$this->input->get('pull');
		if($id!=null){
			$product=$this->product_model->get_by_id($id);
			$where['products.id <>']=$id;
			$where['products.created_at >=']= date('Y-m-d H:i:s',strtotime($product[0]->created_at));
		}

		$title=$this->input->get('title');
		if($title!=null){
			$like['title']=$title;
		}

		$aim=$this->input->get('aim');
		if($aim!=null){
			$where['aim']=$aim;
		}


		$select='*,
		products.created_at as created_at,
		products.updated_at as updated_at,
		categories.name as categories_name,
		cities.name as cities_name,
		products.id as id,
		products.user_id as user_id';

		if($this->input->get('radius')!=0){
			$lat=$this->input->get('lat');
			$lng=$this->input->get('lng');
			$select.=','.'( 6371 * acos( cos( radians('.$lat.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( lat ) ) ) )
			AS distance ';
			$first=false;
			$offset=false;
		}

		$user_id=$this->input->get('user_id');
		if($user_id!=null){
			$where['products.user_id']=$user_id;
		}

		$data=$this->product_model->get($select,$where,$like,$first,$offset,$order);
		if($data!=null){
			$this->response($data); 
		}else{
			$this->response(array("empty"=>1));
		}
	} 

	function products_post() 
	{ 
		$title=preg_replace('/[\r\n]+/', "", $this->post('title')); 
		$content=preg_replace('/[\r\n]+/', "", $this->input->post('content'));
		$price=$this->post('price');
		$aim=$this->post('aim');
		$categories=$this->post('categories');
		$county=$this->post('county');
		$fb_id=$this->post('fb_id');
		$user_id=$this->post('user_id');
		$city=$this->post('city');
		$lat=$this->post('lat');
		$lng=$this->post('lng');
		$images=array();
		$slug=create_slug($title);
		if($this->product_model->get_by_slug($slug)!=null){
			$slug=$slug.'-'.time();
		}
		$data=array(
			'title'=>$title,
			'price'=>$price,
			'content'=>$content,
			'aim'=>$aim,
			'categories_id'=>$categories,
			'county_id'=>$county,
			'fb_id'=>$fb_id,
			'user_id'=>$user_id,
			'cities_id'=>$city,
			'lat'=>$lat,
			'lng'=>$lng,
			'slug'=>$slug
			);

		$insert_id = $this->product_model->insert($data);
		$image_path=null;
		$thumb=null;


		$upload_folder=create_dir_upload();
		if(isset($_FILES)){
			$config['upload_path'] = $upload_folder;
			$config['allowed_types'] = 'gif|jpg|png|jpeg|JPG|JPEG|GIF|PNG';
			$config['max_size']	= '10000';
			$this->load->library('upload', $config);
		}

		if(isset($_FILES['photo1'])){
			$filename=$_FILES['photo1']['name'];
			$_FILES['photo1']['name']=rename_upload_file($filename);	
			if ($this->upload->do_upload('photo1'))
			{
				$image_path=$upload_folder.'/'.$_FILES['photo1']['name'];
				$thumb=$_FILES['photo1']['name'];
				array_push($images, $image_path);
			}
		}

		if(isset($_FILES['photo2'])){
			$filename=$_FILES['photo2']['name'];
			$_FILES['photo2']['name']=rename_upload_file($filename);	
			if ($this->upload->do_upload('photo2'))
			{
				$image_path =$upload_folder.'/'.$_FILES['photo2']['name'];
				array_push($images, $image_path);
			}
		}

		if(isset($_FILES['photo3'])){
			$filename=$_FILES['photo3']['name'];
			$_FILES['photo3']['name']=rename_upload_file($filename);	
			if ($this->upload->do_upload('photo3'))
			{
				$image_path= $upload_folder.'/'.$_FILES['photo3']['name'];
				array_push($images,$image_path);
			}
		}

		if($insert_id!=0){
			if($image_path!=null){
				$thumb_dir= create_thumb_dir_upload($upload_folder.'/thumbs');
				$config=array(
                            "source_image" => $image_path, //get original image
							"new_image" =>  $thumb_dir, //save as new image //need to create thumbs first
							"maintain_ratio" => true,
							"width" => 136,
							"height" => 136
							);
				$this->load->library('image_lib',$config);
				$this->image_lib->resize();

				$thumb_dir= $thumb_dir.'/'.$thumb;

				$this->product_model->update(array('image_path'=>$thumb_dir), array('id'=>$insert_id));	
				$this->load->model('images_model');

				for ($i=0; $i < count($images); $i++) { 
					$this->images_model->insert(array('path'=>$images[$i],'product_id'=>$insert_id));
				}
			}

			$this->response(array('id'=>$insert_id,'thumb'=>$thumb_dir,'slug'=>$slug));
		}else{
			$this->response(array('ok'=>'0'));
		}
	} 

	function products_put() 
	{ 
		$data = array('this not available'); 
		$this->response($data); 
	} 

	function products_delete() 
	{ 
		$data = array('this not available');
		$this->response($data); 
	}  

	function update_post(){
		if(isset($_POST['id'])){
			$id=$this->input->post('id');
			$title=$this->input->post('title');
			$categories=$this->input->post('categories');
			$content=$this->input->post('content');
			$price=$this->input->post('price');
			$purpose=$this->input->post('purpose');
			$cities_id=$this->input->post('cities');
			$condition=$this->input->post('condition');
			$county=$this->input->post('county');
			$lat=$this->input->post('lat');
			$lng=$this->input->post('lng');
			$data_array=array(
				'title'=>$title,
				'categories_id'=>$categories,
				'content'=>$content,
				'price'=>$price,
				'aim'=>$purpose,
				'cities_id'=>$cities_id,
				'condition'=>$condition,
				'county_id'=>$county,
				'lat'=>$lat,
				'lng'=>$lng
				);

			// if($activated!=null){
			// 	$data_array['activated']=$activated;
			// }
			// if($status!=null){
			// 	$data_array['status']=$status;
			// }else{
			// 	$data_array['status']=null;
			// }
			// if($time_rate!=null){
			// 	$data_array['time_rate']=$time_rate;
			// }else{
			// 	$data_array['time_rate']=null;
			// }
			$this->product_model->update($data_array,array('id'=>$id));
			echo json_encode(array('ok'=>1));
			//$data['obj']=$this->product_model->get_by_id($id);
		}else{
			echo json_encode(array('ok'=>0));
		}
	}

	function rate_post(){
		$user_id=$this->post('user_id');
		$product_id=$this->post('product_id');
		$comment=$this->post('comment');
		$point=$this->post('point');
		$product_user_id=$this->post('product_user_id');
		echo $comment;
		if($user_id!=null && $product_id!=null){
			$this->load->model('rating_model');
			$rate=$this->rating_model->get_by_user_id_and_product_id($user_id,$product_id);
			if($rate!=null){
				$where['user_id']=$user_id;
				$where['product_id']=$product_id;
				$this->rating_model->update(array('point'=>$point,'comment'=>$comment,'product_user_id'=>$product_user_id), $where);
			}else{
				$this->rating_model->insert(
					array('point'=>$point,
						'user_id'=>$user_id,
						'product_id'=>$product_id,
						'comment'=>$comment,
						'product_user_id'=>$product_user_id));
			}
			$rating=$this->rating_model->get_rate_by_product_id($product_id);
			//$this->product_model->update(array('rate'=>$rating),array('id'=>$product_id));
		}
	}

	function avg_rate_get(){
		$product_id=$this->get('product_id');
		$this->load->model('rating_model');
		$rating = $this->rating_model->get_rate_by_product_id($product_id);
		if($rating!=null){
			$this->response(array("rating"=>$rating));
		}else{
			$this->response(array("rating"=>null));
		}
	}

	function user_avg_rate_post(){
		$user_id=$this->get('user_id');
		$this->load->model('rating_model');
		$rating = $this->rating_model->get_rate_by_product_id($product_id);
		if($rating!=null){
			$this->response(array("rating"=>$rating));
		}else{
			$this->response(array("rating"=>null));
		}	
	}

	function delete_post(){
		if(isset($_POST['id'])){
			$id=$this->input->post('id');
			$product=$this->product_model->get_by_id($id);
			if($product!=null){
				$this->load->model('images_model');
				$images=$this->images_model->get_by_product_id($id);
				foreach ($images as $r) {
					try {
						unlink($r->path);
						$this->images_model->remove_by_id($r->id);
					} catch (Exception $e) {
						
					}
				}
				try {
					unlink($product[0]->image_path);
				} catch (Exception $e) {
					
				}
			}
			$this->product_model->remove_by_id($id);
		}
	}

	function comment_get(){
		$id=$this->input->get('id');
		if($id!=null){
			$obj=$this->product_model->get_by_id($id);
			$data['link']=base_url().'products?id='.$obj[0]->id;
			//$data['link']='http://developers.facebook.com/docs/plugins/comments/';
			$this->load->view('comment',$data);
		}else{
			$this->load->view('not_found');
		}
	}
}
?>