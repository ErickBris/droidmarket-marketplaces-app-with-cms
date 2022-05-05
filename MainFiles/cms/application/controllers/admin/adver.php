<?php
class Adver extends CI_Controller{
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
		$this->load->model('adver_model');
		$this->form_validation->set_error_delimiters('<span class="help-inline msg-error" generated="true">', '</span>');
		$this->load->helper('ultils_helper');
	}

	function index(){
		$page     = $this->input->get('page') ? $this->input->get('page') : 0;
		$per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
		$order    = $this->input->get('order') ? $this->input->get('order') : 'DESC';
		$config['total_rows'] = $this->adver_model->total(array(), array());
		$config['base_url']= base_url() . 'admin/adver?order='.$order;
		$config['per_page']=$per_page;
		$data['msg_label']=$this->config->item('msg_label');
		$this->pagination->initialize($config);
		$data['list'] = $this->adver_model->get("*", array(),array(),$page, $per_page, array('advers.id' => 'DESC'));
		$data['page_link'] = $this->pagination->create_links();
		$this->template->write_view('content','backends/advers/index',array('data'=>$data));
		$this->template->render();
	}

	public function create(){
		if(isset($_POST['position']) && !empty($_FILES['banner']['tmp_name'])){
			$data['position']=$this->input->post('position');
			$data['activated']=$this->input->post('state');
			$filename=$_FILES['banner']['name'];
			$_FILES['banner']['name']=rename_upload_file($filename);
			$dir=create_sub_dir_upload('uploads/banners/');//create_dir_upload('uploads/avts/');
			$config['allowed_types'] = 'JPEG|jpg|JPG|png';
			$config['max_size'] = '5000';
			$config['width']     = 100;
			$config['height']   = 100;
			$config['upload_path'] = $dir;
			$this->load->library('upload',$config);
			if (!$this->upload->do_upload('banner')){
				$this->session->set_flashdata('msg_failed',$this->upload->display_errors());
				redirect(base_url().'admin/adver/create');
			}else{	
				$data['path']=$dir.'/'.$_FILES['banner']['name'];
				$insert_id=$this->adver_model->insert($data);
				if($insert_id!=0){
					$this->session->set_flashdata('msg_ok',$this->lang->line('add_successfully'));
					redirect(base_url().'admin/adver/create');
				}
			}
		}
		$this->template->write_view('content','backends/advers/add');
		$this->template->render();
	}


	public function edit(){
		$id=$this->input->get('id');
		if(!is_numeric($id) || $id==null){
			redirect('notfound');
		}
		$data['obj']=$this->adver_model->get_by_id($id);
		if(isset($_POST['id'])){
			$id=$this->input->post('id');
			$position=$this->input->post('position');
			if($position!=null){
				$this->adver_model->update(array('position'=>$position),array('id'=>$id));
				$this->session->set_flashdata('msg_ok',$this->lang->line('edit_successfully'));
			}
			$status=$this->input->post('state');
			if($status!=null){
				$this->adver_model->update(array('activated'=>$status),array('id'=>$id));
				$this->session->set_flashdata('msg_ok',$this->lang->line('edit_successfully'));
			}
			if(!empty($_FILES['banner']['tmp_name'])){
				$filename=$_FILES['banner']['name'];
				$_FILES['banner']['name']=rename_upload_file($filename);
				$dir=create_sub_dir_upload('uploads/avts/');//create_dir_upload('uploads/avts/');
				$config['allowed_types'] = 'JPEG|jpg|JPG|png';
				$config['max_size'] = '5000';
				$config['width']     = 100;
				$config['height']   = 100;
				$config['upload_path'] = $dir;
				$this->load->library('upload',$config);
				if (!$this->upload->do_upload('banner')){
					$this->session->set_flashdata('msg_failed',$this->upload->display_errors());
				}else{	
					if($data['obj']->path!=null){
						try {
							unlink($data['obj']->path);
						} catch (Exception $e) {
							echo $e;
						}
					}
					$path=$dir.'/'.$_FILES['banner']['name'];
					$this->adver_model->update(array('path'=>$path),array('id'=>$id));
					$this->session->set_flashdata('msg_ok',$this->lang->line('add_successfully'));
				}
			}
			redirect(base_url().'admin/adver/edit?id='.$id);
		}
		$this->template->write_view('content','backends/advers/edit',$data);
		$this->template->render();
	}

	public function delete(){
		if(isset($_GET['id'])){
			$id=$this->input->get('id');
			$obj=$this->adver_model->get_by_id($id);
			if($obj[0]->path!=null){
				try {
					unlink($obj[0]->path);
				} catch (Exception $e) {
					
				}
			}
			$this->adver_model->remove_by_id($id);
			redirect('admin/adver');
		}
	}

	public function search(){
		if(isset($_GET['query'])){
			$query=$this->input->get('query');
			$data=parent::getDataView();
			$page     = $this->input->get('page') ? $this->input->get('page') : 0;
			$per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
			$order    = $this->input->get('order') ? $this->input->get('order') : 'DESC';
			$config['total_rows'] = $this->adver_model->total(array(), array('name'=>$query));
			$config['base_url']= base_url() . 'index.php/admin/advers/search?order='.$order.'&query='.$query;
			$config['per_page']=$per_page;
			$data['msg_label']=$this->config->item('msg_label');
			$this->pagination->initialize($config);
			$data['list'] = $this->adver_model->get_by_name($query,$page,$per_page);
			$data['page_link'] = $this->pagination->create_links();
			$data['search_title']='Result search for "'.$query.'"';
			$this->template->write_view('content','backends/advers/index',array('data'=>$data));
			$this->template->render();
		}
	}
}
?>