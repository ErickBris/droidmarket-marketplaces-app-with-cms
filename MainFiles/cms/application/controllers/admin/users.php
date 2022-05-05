<?php
class Users extends CI_Controller{
	function __construct()
	{
		parent::__construct();
		if(!isset($_SESSION['user'])){
			redirect('admin/dashboard');
		}else{
			$user=$_SESSION['user'][0];
			if($user->perm==STAFF || $user->perm==USER){
				redirect('admin/denied');
			}
		}
		$this->load->model('users_model');
		$this->form_validation->set_error_delimiters('<span class="help-inline msg-error" generated="true">', '</span>');
		$this->load->helper('Ultils');
	}

	function index(){
		$page     = $this->input->get('page') ? $this->input->get('page') : 0;
		$per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
		$order    = $this->input->get('order') ? $this->input->get('order') : 'DESC';
		$config['total_rows'] = $this->users_model->total(array(), array());
		$config['base_url']= base_url() . 'admin/users?order='.$order;
		$config['per_page']=$per_page;
		$data['msg_label']=$this->config->item('msg_label');
		$this->pagination->initialize($config);
		$data['list'] = $this->users_model->get("*,", array(),array(),$page, $per_page, array('id' => 'DESC'));
		$data['page_link'] = $this->pagination->create_links();
		$this->template->write_view('content','backends/users/index',array('data'=>$data));
		$this->template->render();
	}

	public function create(){
		$error=null;
		if(isset($_POST['user_name'])){
			$this->form_validation->set_rules('user_name','username', 'trim|required|min_length[5]|max_length[60]|xss_clean|callback_check_username_exist_add');
			$this->form_validation->set_rules('pwd','password', 'trim|required|min_length[5]|max_length[60]|xss_clean');
			$this->form_validation->set_rules('full_name','full name', 'trim|required|min_length[5]|max_length[60]|xss_clean');
			$this->form_validation->set_rules('email','email', 'required|xss_clean|valid_email|callback_check_email_exist_add');
			$this->form_validation->set_rules('address','address', 'trim|required|min_length[5]|max_length[60]|xss_clean');
			$this->form_validation->set_rules('phone','phone', 'numeric|trim|required|min_length[5]|max_length[60]|xss_clean');
			if($this->form_validation->run()!=false){
				$data['user_name']=$this->input->post('user_name');
				$data['pwd']=encrypt_pwd($this->input->post('pwd'));
				$data['full_name']=$this->input->post('full_name');
				$data['email']=$this->input->post('email');
				$data['address']=$this->input->post('address');
				$data['perm']=$this->input->post('perm');
				$insert_id=$this->users_model->insert($data);
				if($insert_id!=0){
					$this->session->set_flashdata('msg_ok',$this->lang->line('add_successfully'));
					redirect(base_url().'admin/users/create');
				}
			}
		}
		$this->template->write_view('content','backends/users/add');
		$this->template->render();
	}

	public function upload(){
		$config['upload_path'] = 'uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$this->load->library('upload', $config);
		if(isset($_FILES['image'])){
			$filename=$_FILES['image']['name'];
			$_FILES['image']['name']=rename_upload_file($filename);
		}
		if ($this->upload->do_upload('image'))
		{
			return 'uploads/'.$_FILES['image']['name'];
		}
		else
		{
			return null;
		}
	}

	public function check_username_exist_add($name){
		$data=$this->users_model->get_by_exact_name($name);
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


	public function check_email_exist_add($email){
		$data=$this->users_model->get_by_exact_email($email);
		if ($data!=null)
		{
			$this->form_validation->set_message('check_email_exist_add', 'This email has exist');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}


	public function check_username_exist_edit(){
		$id=$this->input->post('id');
		$name=$this->input->post('user_name');
		$data=$this->users_model->get_by_name_and_diff_id($id,$name);
		if($data!=null) {
			$this->form_validation->set_message('check_username_exist_edit', 'This name has exist');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function check_email_exist_edit(){
		$id=$this->input->post('id');
		$name=$this->input->post('email');
		$data=$this->users_model->	get_by_exact_email_and_diff_id($id ,$name);
		if($data!=null) {
			$this->form_validation->set_message('check_email_exist_edit', 'This email has exist');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function edit_get(){
		if(isset($_GET['id'])){
			$id=$this->input->get('id');
			$data['obj']=$this->users_model->get_by_id($id);
			$this->template->write_view('content','backends/users/edit',array('data'=>$data));
			$this->template->render();
		}
	}

	public function edit_post(){
		if(isset($_POST['id'])){
			$id=$_POST['id'];
			$data=parent::getDataView();
			$this->form_validation->set_rules('user_name','username', 'trim|required|min_length[5]|max_length[60]|xss_clean|callback_check_username_exist_edit');
			$this->form_validation->set_rules('full_name','full name', 'trim|required|min_length[5]|max_length[60]|xss_clean');
			$this->form_validation->set_rules('email','email', 'trim|required|min_length[5]|max_length[60]|xss_clean|valid_email|callback_check_email_exist_edit');
			$this->form_validation->set_rules('address','address', 'trim|required|min_length[5]|max_length[60]|xss_clean');
			if($this->form_validation->run()){
				$update_data['user_name']=$this->input->post('user_name');
				//$update_data['pwd']=encrypt_pwd($this->input->post('pwd'));
				$update_data['full_name']=$this->input->post('full_name');
				$update_data['email'] = $this->input->post('email');
				$update_data['address'] =$this->input->post('address');
				$update_data['perm'] = $this->input->post('perm');
				$this->users_model->update($update_data,array('id'=>$id));
				$this->session->set_flashdata('msg_ok',$this->lang->line('edit_successfully'));
				redirect(base_url().'admin/users/edit_get?id='.$id);
			}
			$data['obj']=$this->users_model->get_by_id($id);
			$this->template->write_view('content','backends/users/edit',array('data'=>$data));
			$this->template->render();
		}
	}

	public function delete(){
		if(isset($_GET['id'])){
			$id=$this->input->get('id');
			$user=$this->users_model->get_by_id($id);
			if($user[0]->perm != ADMIN){
				try {
					unlink($user[0]->avt);
				} catch (Exception $e) {
					
				}
				$this->users_model->remove_by_id($id);
			}
		}
		redirect('admin/users');
	}

	public function activate(){
		if(isset($_GET['id'])){
			$id=$this->input->get('id');
			$this->users_model->update(array('activated'=>1),array('id'=>$id));
		}
		redirect('admin/users');
	}

	public function lock(){
		if(isset($_GET['id'])){
			$id=$this->input->get('id');
			$this->users_model->update(array('activated'=>0),array('id'=>$id));
		}
		redirect('admin/users');
	}

	public function search(){
		if(isset($_GET['query'])){
			$query=$this->input->get('query');
			$data=parent::getDataView();
			$page     = $this->input->get('page') ? $this->input->get('page') : 0;
			$per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
			$order    = $this->input->get('order') ? $this->input->get('order') : 'DESC';
			$config['total_rows'] = $this->users_model->total(array(), array('user_name'=>$query));
			$config['base_url']= base_url() . 'admin/users/search?order='.$order.'&query='.$query;
			$config['per_page']=$per_page;
			$data['msg_label']=$this->config->item('msg_label');
			$this->pagination->initialize($config);
			$data['list'] = $this->users_model->get_by_name($query,$page,$per_page);
			$data['page_link'] = $this->pagination->create_links();
			$data['search_title']='Result search for "'.$query.'"';
			$this->template->write_view('content','backends/users/index',array('data'=>$data));
			$this->template->render();
		}
	}
}
?>