<?php
class Cities extends CI_Controller{


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
		$this->load->model('cities_model');
		$this->form_validation->set_error_delimiters('<span class="help-inline msg-error" generated="true">', '</span>');
	}

	function index(){
		$page     = $this->input->get('page') ? $this->input->get('page') : 0;
		$per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
		$order    = $this->input->get('order') ? $this->input->get('order') : 'DESC';
		$config['total_rows'] = $this->cities_model->total(array(), array());
		$config['base_url']= base_url() . 'index.php/admin/cities?order='.$order;
		$config['per_page']=$per_page;
		$data['msg_label']=$this->config->item('msg_label');
		$this->pagination->initialize($config);
		//$data['list'] = $this->cities_model->get("*,cities.id as id,cities.name as name,county.name as county, cities.created_at as created_at, cities.updated_at as updated_at", array(),array(),$page, $per_page, array('cities.id' => 'DESC'));
		$data['list'] = $this->cities_model->get("*,cities.id as id,cities.name as name, cities.created_at as created_at, cities.updated_at as updated_at", array(),array(),$page, $per_page, array('cities.id' => 'DESC'));
		$data['page_link'] = $this->pagination->create_links();
		$this->template->write_view('content','backends/cities/index',array('data'=>$data));
		$this->template->render();
	}

	public function create(){
		if(isset($_POST['name'])){
			$data['name']=$this->input->post('name');
			$data['county_id']=$this->input->post('county');
			$this->form_validation->set_rules('name','name', 'trim|required|min_length[2]|max_length[60]|xss_clean');
			$this->form_validation->set_rules('county', 'county', 'trim|required|xss_clean');
			if($this->form_validation->run()!=false){
				$insert_id=$this->cities_model->insert($data);
				if($insert_id!=0){
					$this->session->set_flashdata('msg_ok',$this->lang->line('add_successfully'));
					redirect(base_url().'admin/cities/create');
				}
			}
		}
		$this->load->model('county_model');
		$data['county']=$this->county_model->get("*", false,  false,  false, false, false);
		$this->template->write_view('content','backends/cities/add',$data);
		$this->template->render();
	}

	public function check_name_exist_add($name){
		$data=$this->cities_model->get_by_exact_name($name, 0, 1);
		if ($data!=null)
		{
			$this->form_validation->set_message('check_name_exist_add', 'This name has exist');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function check_name_exist_edit(){
		$id=$this->input->post('id');
		$name=$this->input->post('name');
		$data=$this->cities_model->get_by_name_and_diff_id($id,$name);
		if($data!=null) {
			$this->form_validation->set_message('check_name_exist_edit', 'This name has exist');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function edit_get(){
		if(isset($_GET['id'])){
			$id=$this->input->get('id');
			$data['obj']=$this->cities_model->get_by_id($id);
			$this->load->model('county_model');
			$data['county']=$this->county_model->get("*", false,  false,  false, false, false);
			$this->template->write_view('content','backends/cities/edit',$data);
			$this->template->render();
		}
	}

	public function edit_post(){
		if(isset($_POST['id'])){
			$id=$this->input->post('id');
			$name=$this->input->post('name');
			$county=$this->input->post('county');		
			$this->form_validation->set_rules('name','name', 'trim|required|min_length[2]|max_length[60]|xss_clean');
			//$this->form_validation->set_rules('county', 'county', 'trim|required|xss_clean');
			if($this->form_validation->run()){
				//$this->cities_model->update(array('name'=>$name,'county_id'=>$county),array('id'=>$id));
				$this->cities_model->update(array('name'=>$name),array('id'=>$id));
				$this->session->set_flashdata('msg_ok',$this->lang->line('edit_successfully'));
				redirect(base_url().'admin/cities/edit_get?id='.$id);
			}
			$data['obj']=$this->cities_model->get_by_id($id);
			$this->load->model('county_model');
			$data['county']=$this->county_model->get("*", false,  false,  false, false, false);
			$this->template->write_view('content','backends/cities/edit',$data);
			$this->template->render();
		}
	}

	public function delete(){
		if(isset($_GET['id'])){
			$id=$this->input->get('id');
			$this->cities_model->remove_by_id($id);
			redirect('admin/cities');
		}
	}

	public function search(){
		if(isset($_GET['query'])){
			$query=$this->input->get('query');
			$data=parent::getDataView();
			$page     = $this->input->get('page') ? $this->input->get('page') : 0;
			$per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
			$order    = $this->input->get('order') ? $this->input->get('order') : 'DESC';
			$config['total_rows'] = $this->cities_model->total(array(), array('name'=>$query));
			$config['base_url']= base_url() . 'index.php/admin/cities/search?order='.$order.'&query='.$query;
			$config['per_page']=$per_page;
			$data['msg_label']=$this->config->item('msg_label');
			$this->pagination->initialize($config);
			$data['list'] = $this->cities_model->get_by_name($query,$page,$per_page);
			$data['page_link'] = $this->pagination->create_links();
			$data['search_title']='Result search for "'.$query.'"';
			$this->template->write_view('content','backends/cities/index',array('data'=>$data));
			$this->template->render();
		}
	}

	public function get_city(){
		if(isset($_POST['county_id'])){
			$county_id=$this->input->post('county_id');
			$this->load->model('cities_model');
			$data['list']=$this->cities_model->get_by_county_id($county_id);
			$this->template->write_view('content','backends/cities/list',$data);
			$this->template->render();
		}
	}
}
?>