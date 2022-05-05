<?php
class Categories extends CI_Controller{
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
		$this->load->model('categories_model');
		$this->form_validation->set_error_delimiters('<span class="help-inline msg-error" generated="true">', '</span>');
	}

	function index(){
		$page     = $this->input->get('page') ? $this->input->get('page') : 0;
		$per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
		$order    = $this->input->get('order') ? $this->input->get('order') : 'DESC';
		$config['total_rows'] = $this->categories_model->total(array(), array());
		$config['base_url']= base_url() . 'index.php/admin/categories?order='.$order;
		$config['per_page']=$per_page;
		$data['msg_label']=$this->config->item('msg_label');
		$this->pagination->initialize($config);
		$data['list'] = $this->categories_model->get("*,", array(),array(),$page, $per_page, array('id' => 'DESC'));
		$data['page_link'] = $this->pagination->create_links();
		$this->template->write_view('content','backends/categories/index',array('data'=>$data));
		$this->template->render();
	}

	public function create(){
		if(isset($_POST['name'])){
			$data['name']=$this->input->post('name');
			$data['parent_id']=$this->input->post('parent_id');
			$this->form_validation->set_rules('name','name', 'trim|required|min_length[2]|max_length[60]|xss_clean|callback_check_username_exist_add');
			if($this->form_validation->run()!=false){
				$insert_id=$this->categories_model->insert($data);
				if($insert_id!=0){
					$this->session->set_flashdata('msg_ok',$this->lang->line('add_successfully'));
					redirect(base_url().'admin/categories/create');
				}
			}
		}
		$this->load->model('categories_model');
		$data['parent_cat']=$this->categories_model->get_by_parent_id(0);
		$this->template->write_view('content','backends/categories/add',$data);
		$this->template->render();
	}

	public function check_username_exist_add($name){
		$data=$this->categories_model->get_by_exact_name($name, 0, 1);
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
		$data=$this->categories_model->get_by_name_and_diff_id($id,$name);
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
			$data['obj']=$this->categories_model->get_by_id($id);
			$this->load->model('categories_model');
			$data['parent_cat']=$this->categories_model->get_by_parent_id_and_diff_id(0,$id);
			$this->template->write_view('content','backends/categories/edit',$data);
			$this->template->render();
		}
	}

	public function edit_post(){
		if(isset($_POST['id'])){
			$id=$_POST['id'];
			$name=$_POST['name'];		
			$this->form_validation->set_rules('name','name', 'trim|required|min_length[2]|max_length[60]|xss_clean|callback_check_username_exist_edit');
			if($this->form_validation->run()){
				$parent_id=$this->input->post('parent_id');
				$this->categories_model->update(array('name'=>$name,'parent_id'=>$parent_id),array('id'=>$id));
				$this->session->set_flashdata('msg_ok',$this->lang->line('edit_successfully'));
				redirect(base_url().'admin/categories/edit_get?id='.$id);
			}
			$data['obj']=$this->categories_model->get_by_id($id);
			$this->load->model('categories_model');
			$data['parent_cat']=$this->categories_model->get_by_parent_id(0);
			$this->template->write_view('content','backends/categories/edit',$data);
			$this->template->render();
		}
	}

	public function delete(){
		if(isset($_GET['id'])){
			$id=$this->input->get('id');
			$this->categories_model->remove_by_id($id);
			redirect('admin/categories');
		}
	}

	public function search(){
		if(isset($_GET['query'])){
			$query=$this->input->get('query');
			$data=parent::getDataView();
			$page     = $this->input->get('page') ? $this->input->get('page') : 0;
			$per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
			$order    = $this->input->get('order') ? $this->input->get('order') : 'DESC';
			$config['total_rows'] = $this->categories_model->total(array(), array('name'=>$query));
			$config['base_url']= base_url() . 'index.php/admin/categories/search?order='.$order.'&query='.$query;
			$config['per_page']=$per_page;
			$data['msg_label']=$this->config->item('msg_label');
			$this->pagination->initialize($config);
			$data['list'] = $this->categories_model->get_by_name($query,$page,$per_page);
			$data['page_link'] = $this->pagination->create_links();
			$data['search_title']='Result search for "'.$query.'"';
			$this->template->write_view('content','backends/categories/index',array('data'=>$data));
			$this->template->render();
		}
	}
}
?>