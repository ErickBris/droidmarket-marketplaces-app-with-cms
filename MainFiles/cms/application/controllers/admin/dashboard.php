<?php
class Dashboard extends CI_Controller{
	function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->form_validation->set_error_delimiters('<div class="error-line msg-error">', '</div>');
		$this->load->helper('Ultils');
	}

	function index(){
		if(!isset($_SESSION['user']) || $_SESSION['user']==null){
			$this->load->view('backends/login');
		}else{
			redirect('admin/products');
		}
	}

	function login(){
		if(isset($_POST['user_name']) && isset($_POST['pwd'])){
			$pass_data=null;
			$this->form_validation->set_rules('user_name','username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('pwd','password', 'trim|required|xss_clean');
			if($this->form_validation->run()!=false){
				$user_name=$_POST['user_name'];
				$pwd=$_POST['pwd'];
				$this->load->model('users_model');
				$this->load->helper('ultils');
				$data=$this->users_model->get_by_username_and_pwd($user_name,encrypt_pwd($pwd));
				if($data!=null){
					$_SESSION[user]=$data;
					echo $this->db->last_query();
					redirect('admin/dashboard');
				}else{
					$pass_data['error_msg']='<div class="error-line">Wrong password or username, try again</div>';
				}
			}
			$this->load->view('backends/login',$pass_data);
		}else{
			redirect('admin/images');
		}
	}

	function logout(){
		if(isset($_SESSION['user'])){
			unset($_SESSION['user']);
			redirect('admin/dashboard');
		}else{
			redirect('admin/dashboard');
		}
	}

	public function update_profile(){
		if(isset($_SESSION['user'])){
			$user=$_SESSION['user'][0];
			$this->form_validation->set_rules('full_name','full name', 'trim|required|min_length[5]|max_length[60]|xss_clean');
			$this->form_validation->set_rules('email','email', 'trim|required|min_length[5]|max_length[60]|xss_clean|valid_email|callback_check_email_exist_edit');
			$this->form_validation->set_rules('address','address', 'trim|required|min_length[5]|max_length[60]|xss_clean');
			if($this->form_validation->run()){
				$update_data['full_name']=$this->input->post('full_name');
				$update_data['email'] = $this->input->post('email');
				$update_data['address'] =$this->input->post('address');
				$this->users_model->update($update_data,array('id'=>$user->id));
				$this->session->set_flashdata('msg_ok',$this->lang->line('update_successfully'));
				redirect('admin/dashboard/update_profile');
			}
			$data['obj']=$this->users_model->get_by_id($user->id);
			$this->template->write_view('content','backends/users/update_profile',$data);
			$this->template->render();
		}else{
			redirect('admin/dashboard');
		}
	}

	public function update_pwd(){
		if(isset($_SESSION['user'])){
			$user=$_SESSION['user'][0];
			$this->form_validation->set_rules('old_pwd','old password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_pwd','new password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('cfm_pwd','confirm password', 'trim|required|xss_clean|callback_check_equal_less['.$this->input->post('new_pwd').']');
			if($this->form_validation->run()){
				$old_pwd=$this->input->post('old_pwd');
				if(encrypt_pwd($old_pwd)!=$user->pwd){
					$data['error_msg']="Your old password incorrect, updated failed";
				}else{
					$new_pwd=$this->input->post('new_pwd');
					$update_data['pwd']=encrypt_pwd($new_pwd);
					$this->users_model->update($update_data,array('id'=>$user->id));
					$data['success_msg']="Update success";
				}
			}
			$data['obj']=$this->users_model->get_by_id($user->id);
			$this->template->write_view('content','backends/users/update_pwd',$data);
			$this->template->render();
		}else{
			redirect('admin/dashboard');
		}
	}

	function check_equal_less($second_field,$first_field)
	{
		$new_pwd=$this->input->post('new_pwd');
		$cfm_pwd=$this->input->post('cfm_pwd');
		if ($new_pwd!=$cfm_pwd)
		{
			$this->form_validation->set_message('check_equal_less', 'The confirm password need the same confirm password');
			return false;       
		}
		else
		{
			return true;
		}
	}

	function settings(){
		$this->load->helper('settings');
	}

	function mail(){
		$this->load->helper('email_ultils');
		//send_verified_mail("álkdjsad","luann4099@gmail.com");
		send_enquiry();
	}
}
?>