<?php

/**

 *

 */

class Users_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get($select = "*", $array_where = false, $array_like = false, $first = false, $offset = false, $order_by = false) {
		$data = array();
		if( $order_by != false){
			$order = key($order_by);
			if ($order != null) {
				$sort = $order_by[$order];
				$this -> db -> order_by($order, $sort);
			}
		}

		$this -> db -> select($select);
		$this -> db -> from('users');
		if($array_where != false)
			$this -> db -> where($array_where);
		if($array_like != false)
			$this -> db -> like($array_like);
		if($offset != false){
			$this -> db -> limit($offset, $first);
		}

		$query = $this -> db -> get();
		if ($query -> num_rows() > 0) {
			foreach ($query->result() as $rows) {
				$data[] = $rows;
			}
			$query -> free_result();
			return $data;
		} else {
			return null;
		}
	}

	function total($array_where, $array_like) {
		$this -> db -> select('count(*) as total');
		$this -> db -> where($array_where);
		$this -> db -> like($array_like);
		$this -> db -> from('users');
		$query = $this -> db -> get();
		$rows = $query -> result();
		$query -> free_result();
		return $rows[0] -> total;
	}

	function get_by_id($id) {
		$select = '*';
		$array_where = array('id' => $id);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_fb_id($id) {
		$select = '*';
		$array_where = array('fb_id' => $id,'activated'=>1);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function fb_exist($fb_id,$email) {
		$select = '*';
		$array_where = array('fb_id' => $fb_id,'email'=>$email,'activated'=>1);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_exact_name($name){
		$select = '*';
		$array_like=array();
		$array_where = array('user_name'=>$name);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}


	function get_by_exact_full_name($name){
		$select = '*';
		$array_like=array();
		$array_where = array('full_name'=>$name);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}


	function get_by_exact_email($name){
		$select = '*';
		$array_like=array();
		$array_where = array('email' => $name);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_name($name, $first, $offset) {
		$select = '*';
		$array_where = array();
		$array_like = array('user_name'=>$name);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, $first, $offset, $order_by);
	}

	function get_by_name_and_diff_id($id ,$name){
		$select = '*';
		$array_where = array('user_name'=>$name,'id <>'=>$id);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}


	function get_by_exact_email_and_diff_id($id ,$name){
		$select = '*';
		$array_like=array();
		$array_where = array('email' => $name,'id <>'=>$id);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}


	function get_by_exact_phone_and_diff_id($id ,$phone){
		if($phone==null || $phone==""){
			return null;
		}
		$select = '*';
		$array_like=array();
		$array_where = array('phone' => $phone,'id <>'=>$id);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_exact_web_and_diff_id($id ,$website){
		if($website==null || $website==""){
			return null;
		}
		$select = '*';
		$array_like=array();
		$array_where = array('website' => $website,'id <>'=>$id);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}


	function get_by_id_and_name($id, $name) {
		$select = '*';
		$array_like = array();
		$array_where= array('user_name'=>$name,'id'=>$id);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like,0, 1, $order_by);
	}

	function get_by_username_and_pwd($username, $pwd) {
		$select = '*';
		$array_like = array();
		$array_where= array('pwd'=>$pwd,'user_name'=>$username);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like,0, 1, $order_by);
	}


	function get_by_email_and_pwd($email, $pwd) {
		$select = '*';
		$array_like = array();
		$array_where= array('pwd'=>$pwd,'email'=>$email,'activated'=>1);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like,0, 1, $order_by);
	}


	function insert($data_array) {
		$data_array['created_at']=date('Y-m-d H:i:s');
		$data_array['updated_at']=date('Y-m-d H:i:s');
		$this -> db -> insert('users', $data_array);
		return $this -> db -> insert_id();
	}

	public function remove($arr_where) {
		$this -> db -> where($arr_where);
		$this -> db -> delete('users');
		return $this->db->affected_rows();
	}

	public function remove_by_id($id) {
		$array_where = array('id' => $id);
		return $this -> remove($array_where);
	}

	function update($data_array, $array_where) {
		$this -> db -> where($array_where);
		$data_array['updated_at']=date('Y-m-d H:i:s');
		$this -> db -> update('users', $data_array);
	}
}
?>
