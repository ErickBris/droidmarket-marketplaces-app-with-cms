<?php
class Products extends CI_Controller{
	function __construct()
	{
		parent::__construct();
		if(!isset($_SESSION['user'])){
			redirect('admin/dashboard');
		}else{
			$user=$_SESSION['user'][0];
			if($user->perm==USER){
				redirect('admin/denied');
			}
		}
		$this->load->model('product_model');
		$config['upload_path'] = 'uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|JPG|JPEG|GIF|PNG';
		$config['max_size']	= '2000';
		$this->load->library('upload', $config);
		$this->load->helper('Ultils');
		$this->form_validation->set_error_delimiters('<span class="help-inline msg-error" generated="true">', '</span>');
	}

	function index(){
		$page     = $this->input->get('page') ? $this->input->get('page') : 0;
		$per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
		$order    = $this->input->get('order') ? $this->input->get('order') : 'DESC';
		$config['base_url']= base_url() . 'index.php/admin/products?order='.$order;
		$config['per_page']=$per_page;
		$data['msg_label']=$this->config->item('msg_label');
		$config['total_rows'] = $this->product_model->total(array(), array());
		$this->pagination->initialize($config);
		$data['list'] = $this->product_model->get("*,products.id as id,products.activated as activated", array(),array(),$page, $per_page, array('products.id' => 'DESC'));
		$data['page_link'] = $this->pagination->create_links();
		$this->template->write_view('content','backends/products/index',array('data'=>$data));
		$this->template->render();
	}

	public function create(){
		$error=null;
		$images=array();
		$image_path=null;
		$insert_id =0;
		$thumb=null;
		if(isset($_SESSION['user'])){
			if(isset($_POST['title'])){
				$user=$_SESSION['user'][0];
				$this->form_validation->set_rules('title','title', 'trim|required|min_length[5]|max_length[100]|xss_clean');
				$this->form_validation->set_rules('price', 'price', 'trim|numeric|xss_clean');
				$this->form_validation->set_rules('aim', 'aim', 'trim|required|xss_clean');
				$this->form_validation->set_rules('content', 'content', 'trim|required|min_length[5]|max_length[2000]|xss_clean');
				$this->form_validation->set_rules('provinces', 'provinces', 'trim|required|xss_clean');
				$this->form_validation->set_rules('cities', 'cities', 'trim|required|xss_clean');
				$this->form_validation->set_rules('categories', 'categories', 'trim|required|xss_clean');

				if($this->form_validation->run()!=false){
					$data['title']=preg_replace('/[\r\n]+/', "", $this->input->post('title')); 
					$data['price']=$this->input->post('price');
					$data['aim']=$this->input->post('aim');
					$data['content']=preg_replace('/[\r\n]+/', "", htmlspecialchars($this->input->post('content'))); 
					$data['county_id']=$this->input->post('provinces');
					$data['categories_id']=$this->input->post('categories');
					$data['user_id']=$user->id;
					$data['cities_id']=$this->input->post('cities');
					$insert_id = $this->product_model->insert($data);
					$allowed =  array('gif','png' ,'jpg');
					$filename = $_FILES['image']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(in_array($ext,$allowed)) {
						$upload_result=self::upload();
						if($upload_result!=null){
							$image_path=$upload_result;
							array_push($images, $upload_result);
							$this->form_validation->set_rules('image', 'image', 'callback_upload');
						}else{
							$error['error_upload_file']="Can not upload file, please check again";
						}
					}else{
						$error['eror_upload_file']="Your upload file contains invalid allow upload file type";
					}

					$filename = $_FILES['image1']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(in_array($ext,$allowed)) {
						$upload_result=self::upload1();
						if($upload_result!=null){
							$image_path=$upload_result;
							array_push($images, $upload_result);
							$this->form_validation->set_rules('image', 'image', 'callback_upload');
						}else{
							$error['error_upload_file_1']="Can not upload file, please check again";
						}
					}else{
						$error['eror_upload_file_1']="Your upload file contains invalid allow upload file type";
					}

					$filename = $_FILES['image2']['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(in_array($ext,$allowed)) {
						$upload_result=self::upload2();
						if($upload_result!=null){
							$image_path=$upload_result;
							array_push($images, $upload_result);
							$this->form_validation->set_rules('image', 'image', 'callback_upload');
						}else{
							$error['error_upload_file_2']="Can not upload file, please check again";
						}
					}else{
						$error['eror_upload_file_2']="Your upload file contains invalid allow upload file type";
					}

					if($insert_id!=0){
						if($image_path!=null){
							$config=array(
                            "source_image" => 'uploads/'.$image_path, //get original image
							"new_image" =>  "uploads/thumbs", //save as new image //need to create thumbs first
							"maintain_ratio" => true,
							"width" => 200,
							"height" => 200
							);
							$this->load->library('image_lib',$config);
							$this->image_lib->resize();
							$image_path= 'uploads/thumbs/'.$image_path;
							$this->product_model->update(array('image_path'=>$image_path), array('id'=>$insert_id));	
							$this->load->model('images_model');
							for ($i=0; $i < count($images); $i++) { 
								$this->images_model->insert(array('path'=>'uploads/'.$images[$i],'product_id'=>$insert_id));
							}
						}
						$this->session->set_flashdata('msg_ok',$this->lang->line('add_successfully'));
						redirect('admin/products/create');
					}
				}
			}
			;	}else{
				redirect('admin/dashboard');
			}
			$this->load->model('county_model');
			$data['provinces']=$this->county_model->get();
			$this->load->model('categories_model');
			$data['categories']=$this->categories_model->get();
			$this->template->write_view('content','backends/products/add',array('data'=>$data,'error'=>$error));
			$this->template->render();
		}

		public function upload(){
			if(isset($_FILES['image'])){
				$filename=$_FILES['image']['name'];
				$_FILES['image']['name']=rename_upload_file($filename);
			}
			if ($this->upload->do_upload('image'))
			{
				return $_FILES['image']['name'];
			}
			else
			{
				return null;
			}
		}

		public function upload1(){
			if(isset($_FILES['image1'])){
				$filename=$_FILES['image1']['name'];
				$_FILES['image1']['name']=rename_upload_file($filename);
			}
			if ($this->upload->do_upload('image1'))
			{
				return $_FILES['image1']['name'];
			}
			else
			{
				return null;
			}
		}

		public function upload2(){
			if(isset($_FILES['image2'])){
				$filename=$_FILES['image2']['name'];
				$_FILES['image2']['name']=rename_upload_file($filename);
			}
			if ($this->upload->do_upload('image2'))
			{
				return $_FILES['image2']['name'];
			}
			else
			{
				return null;
			}
		}

		public function check_username_exist_add($name){
			$data=$this->product_model->get_by_exact_name($name, 0, 1);
			if ($data!=null)
			{
				$this->form_validation->set_message('check_username_exist_add', 'This name has exist');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}

		public function check_username_exist_edit(){
			$id=$this->input->post('id');
			$name=$this->input->post('name');
			$data=$this->product_model->get_by_name_and_diff_id($id,$name);
			if($data!=null) {
				$this->form_validation->set_message('check_username_exist_edit', 'This name has exist');
				return FALSE;
			} else {
				return TRUE;
			}
		}

		public function edit_get(){
			if(isset($_GET['id'])){
				$id=$this->input->get('id');
				$data=parent::getDataView();
				$data['obj']=$this->product_model->get_by_id($id);
				$this->blade->render('backends/products/edit',array('data'=>$data));
			}
		}

		public function edit_post(){
			if(isset($_POST['id'])){
				$id=$_POST['id'];
				$name=$_POST['name'];
				$data=parent::getDataView();
				$this->form_validation->set_rules('name','name', 'trim|required|min_length[5]|max_length[60]|xss_clean|callback_check_username_exist_edit');
				if($this->form_validation->run()){
					$this->product_model->update(array('name'=>$name),array('id'=>$id));
				}
				$data['obj']=$this->product_model->get_by_id($id);
				$this->blade->render('backends/products/edit',array('data'=>$data));
			}
		}

		public function delete(){
			if(isset($_GET['id'])){
				$id=$this->input->get('id');
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

				redirect('admin/products');
			}
		}

		public function activate(){
			if(isset($_GET['id'])){
				$id=$this->input->get('id');
				echo $id;
				$this->product_model->update(array('activated'=>1),array('id'=>$id));
			}
			redirect('admin/products');
		}

		public function lock(){
			if(isset($_GET['id'])){
				$id=$this->input->get('id');
				$this->product_model->update(array('activated'=>0),array('id'=>$id));
			}
			redirect('admin/products');
		}

		public function search(){
			if(isset($_GET['query'])){
				$query=$this->input->get('query');
				$data=parent::getDataView();
				$page     = $this->input->get('page') ? $this->input->get('page') : 0;
				$per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
				$order    = $this->input->get('order') ? $this->input->get('order') : 'DESC';
				$config['total_rows'] = $this->product_model->total(array(), array('title'=>$query));
				$config['base_url']= base_url() . 'index.php/admin/products/search?order='.$order.'&query='.$query;
				$config['per_page']=$per_page;
				$data['msg_label']=$this->config->item('msg_label');
				$this->pagination->initialize($config);
				$data['list'] = $this->product_model->get_by_name($query,$page,$per_page);
				$data['page_link'] = $this->pagination->create_links();
				$data['search_title']='Result search for "'.$query.'"';
				$this->template->write_view('content','backends/products/index',array('data'=>$data));
				$this->template->render();
			}
		}
	}
	?>